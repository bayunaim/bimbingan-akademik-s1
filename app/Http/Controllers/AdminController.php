<?php

namespace App\Http\Controllers;

use App\Exports\DosenTemplateExport;
use App\Exports\MahasiswaTemplateExport;
use App\Imports\DosenImport;
use App\Imports\MahasiswaImport;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    // ==========================================
    // HELPER SCOPE PRODI
    // Admin pusat = semua prodi, Kaprodi = hanya prodinya sendiri
    // ==========================================
    private function scopedUsers(string $role)
    {
        $q = User::where('role', $role);
        $me = auth()->user();
        if ($me->isKaprodi()) {
            $q->where('prodi_id', $me->prodi_id);
        }
        return $q;
    }

    /** Prodi yang boleh dipilih oleh user yang login */
    private function prodiOptions()
    {
        $me = auth()->user();
        return $me->isKaprodi()
            ? Prodi::where('id', $me->prodi_id)->get()
            : Prodi::orderBy('kode')->get();
    }

    /** Validasi prodi_id; kaprodi selalu dipaksa ke prodinya sendiri */
    private function resolveProdiId(Request $request): ?int
    {
        $me = auth()->user();
        if ($me->isKaprodi()) {
            return $me->prodi_id;
        }
        $request->validate(['prodi_id' => 'required|exists:prodis,id']);
        return (int) $request->prodi_id;
    }

    private function uploadFoto(Request $request, ?User $old = null): ?string
    {
        if (!$request->hasFile('foto')) {
            return $old?->foto;
        }
        if ($old?->foto && File::exists(public_path($old->foto))) {
            File::delete(public_path($old->foto));
        }
        $file = $request->file('foto');
        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(public_path('storage/foto_profil'), $filename);
        return 'storage/foto_profil/' . $filename;
    }

    // ==========================================
    // KELOLA DATA DOSEN
    // ==========================================
    public function indexDosen()
    {
        $dosens = $this->scopedUsers('dosen')->with('prodi')->latest()->get();
        $prodis = $this->prodiOptions();
        return view('admin.dosen.index', compact('dosens', 'prodis'));
    }

    public function storeDosen(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_induk' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $prodiId = $this->resolveProdiId($request);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_induk' => $request->nomor_induk,
            'prodi_id' => $prodiId,
            'foto' => $this->uploadFoto($request),
            'password' => Hash::make($request->password ?: 'password123'),
            'role' => 'dosen',
        ]);

        return back()->with('success', 'Data Dosen berhasil ditambahkan!');
    }

    public function editDosen($id)
    {
        $dosen = $this->scopedUsers('dosen')->findOrFail($id);
        $prodis = $this->prodiOptions();
        return view('admin.dosen.edit', compact('dosen', 'prodis'));
    }

    public function updateDosen(Request $request, $id)
    {
        $dosen = $this->scopedUsers('dosen')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dosen->fill([
            'name' => $request->name,
            'nomor_induk' => $request->nomor_induk,
            'email' => $request->email,
            'prodi_id' => $this->resolveProdiId($request),
        ]);
        $dosen->foto = $this->uploadFoto($request, $dosen);

        if ($request->filled('password')) {
            $dosen->password = Hash::make($request->password);
        }
        $dosen->save();

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen ' . $dosen->name . ' berhasil diperbarui!');
    }

    public function destroyDosen($id)
    {
        $dosen = $this->scopedUsers('dosen')->findOrFail($id);

        if ($dosen->foto && File::exists(public_path($dosen->foto))) {
            File::delete(public_path($dosen->foto));
        }
        $dosen->delete();

        return back()->with('success', 'Data Dosen berhasil dihapus!');
    }

    // ==========================================
    // KELOLA DATA MAHASISWA & PLOTTING DOSEN
    // ==========================================
    public function indexMahasiswa()
    {
        $mahasiswas = $this->scopedUsers('mahasiswa')->with(['dosenPembimbing', 'prodi'])->latest()->get();
        $dosens = $this->scopedUsers('dosen')->get();
        $prodis = $this->prodiOptions();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'dosens', 'prodis'));
    }

    /** Dosen pembimbing harus berasal dari prodi yang sama dengan mahasiswa */
    private function validateDosenSeprodi(Request $request, ?int $prodiId): void
    {
        if (!$request->filled('dosen_id')) {
            return;
        }
        $valid = User::where('role', 'dosen')
            ->where('id', $request->dosen_id)
            ->where('prodi_id', $prodiId)
            ->exists();

        abort_unless($valid, 422, 'Dosen pembimbing harus dari prodi yang sama dengan mahasiswa.');
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_induk' => 'required|string|max:255',
            'dosen_id' => 'nullable|exists:users,id',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $prodiId = $this->resolveProdiId($request);
        $this->validateDosenSeprodi($request, $prodiId);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_induk' => $request->nomor_induk,
            'dosen_id' => $request->dosen_id,
            'prodi_id' => $prodiId,
            'foto' => $this->uploadFoto($request),
            'password' => Hash::make($request->password ?: 'password123'),
            'role' => 'mahasiswa',
        ]);

        return back()->with('success', 'Data Mahasiswa dan Plotting berhasil disimpan!');
    }

    public function editMahasiswa($id)
    {
        $mahasiswa = $this->scopedUsers('mahasiswa')->findOrFail($id);
        $dosens = $this->scopedUsers('dosen')->get();
        $prodis = $this->prodiOptions();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosens', 'prodis'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $mahasiswa = $this->scopedUsers('mahasiswa')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'dosen_id' => 'nullable|exists:users,id',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $prodiId = $this->resolveProdiId($request);
        $this->validateDosenSeprodi($request, $prodiId);

        $mahasiswa->fill([
            'name' => $request->name,
            'nomor_induk' => $request->nomor_induk,
            'email' => $request->email,
            'prodi_id' => $prodiId,
            'dosen_id' => $request->dosen_id,
        ]);
        $mahasiswa->foto = $this->uploadFoto($request, $mahasiswa);

        if ($request->filled('password')) {
            $mahasiswa->password = Hash::make($request->password);
        }
        $mahasiswa->save();

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa ' . $mahasiswa->name . ' berhasil diperbarui!');
    }

    public function destroyMahasiswa($id)
    {
        $mahasiswa = $this->scopedUsers('mahasiswa')->findOrFail($id);

        if ($mahasiswa->foto && File::exists(public_path($mahasiswa->foto))) {
            File::delete(public_path($mahasiswa->foto));
        }
        $mahasiswa->delete();

        return back()->with('success', 'Data Mahasiswa berhasil dihapus!');
    }

    // ==========================================
    // EXCEL
    // ==========================================
    public function templateDosen()
    {
        return Excel::download(new DosenTemplateExport, 'Template_Dosen.xlsx');
    }

    public function importDosen(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv']);
        $import = new DosenImport(auth()->user());
        Excel::import($import, $request->file('file_excel'));

        return back()->with('success', $import->summary());
    }

    public function templateMahasiswa()
    {
        return Excel::download(new MahasiswaTemplateExport, 'Template_Mahasiswa_Plotting.xlsx');
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv']);
        $import = new MahasiswaImport(auth()->user());
        Excel::import($import, $request->file('file_excel'));

        return back()->with('success', $import->summary());
    }
}
