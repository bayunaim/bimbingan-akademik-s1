<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;
use App\Imports\MahasiswaImport;
use App\Exports\DosenTemplateExport;
use App\Exports\MahasiswaTemplateExport;

class AdminController extends Controller
{
    // ==========================================
    // KELOLA DATA DOSEN
    // ==========================================
    public function indexDosen()
    {
        $dosens = User::where('role', 'dosen')->latest()->get();
        return view('admin.dosen.index', compact('dosens'));
    }

    public function storeDosen(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_induk' => 'required|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'password' => 'nullable|min:6', // Password opsional, default ke password123 jika kosong
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/foto_profil'), $filename);
            $fotoPath = 'storage/foto_profil/' . $filename;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_induk' => $request->nomor_induk,
            'prodi' => $request->prodi,
            'foto' => $fotoPath,
            'password' => $request->password ? Hash::make($request->password) : Hash::make('password123'),
            'role' => 'dosen',
        ]);

        return back()->with('success', 'Data Dosen berhasil ditambahkan!');
    }

    // ==========================================
    // KELOLA DATA MAHASISWA & PLOTTING DOSEN
    // ==========================================
    public function indexMahasiswa()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->with('dosenPembimbing')->latest()->get();
        $dosens = User::where('role', 'dosen')->get();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'dosens'));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_induk' => 'required|string|max:255',
            'dosen_id' => 'nullable|exists:users,id',
            'prodi' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/foto_profil'), $filename);
            $fotoPath = 'storage/foto_profil/' . $filename;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nomor_induk' => $request->nomor_induk,
            'dosen_id' => $request->dosen_id,
            'prodi' => $request->prodi,
            'foto' => $fotoPath,
            'password' => $request->password ? Hash::make($request->password) : Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        return back()->with('success', 'Data Mahasiswa dan Plotting berhasil disimpan!');
    }

    // --- FITUR EXCEL DOSEN ---
    public function templateDosen()
    {
        return Excel::download(new DosenTemplateExport, 'Template_Dosen.xlsx');
    }

    public function importDosen(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new DosenImport, $request->file('file_excel'));
        return back()->with('success', 'Data Dosen berhasil di-import massal!');
    }

    // --- FITUR EXCEL MAHASISWA ---
    public function templateMahasiswa()
    {
        return Excel::download(new MahasiswaTemplateExport, 'Template_Mahasiswa_Plotting.xlsx');
    }

    public function importMahasiswa(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new MahasiswaImport, $request->file('file_excel'));
        return back()->with('success', 'Data Mahasiswa & Plotting berhasil di-import massal!');
    }

    public function editMahasiswa($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);
        $dosens = User::where('role', 'dosen')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosens'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'prodi' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $mahasiswa = User::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && File::exists(public_path($mahasiswa->foto))) {
                File::delete(public_path($mahasiswa->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/foto_profil'), $filename);
            $mahasiswa->foto = 'storage/foto_profil/' . $filename;
        }

        $mahasiswa->name = $request->name;
        $mahasiswa->nomor_induk = $request->nomor_induk;
        $mahasiswa->email = $request->email;
        $mahasiswa->prodi = $request->prodi;
        $mahasiswa->dosen_id = $request->dosen_id;

        if ($request->filled('password')) {
            $mahasiswa->password = Hash::make($request->password);
        }

        $mahasiswa->save();

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa '.$mahasiswa->name.' berhasil diperbarui!');
    }

    public function editDosen($id)
    {
        $dosen = User::where('role', 'dosen')->findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function updateDosen(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'prodi' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dosen = User::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($dosen->foto && File::exists(public_path($dosen->foto))) {
                File::delete(public_path($dosen->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/foto_profil'), $filename);
            $dosen->foto = 'storage/foto_profil/' . $filename;
        }

        $dosen->name = $request->name;
        $dosen->nomor_induk = $request->nomor_induk;
        $dosen->email = $request->email;
        $dosen->prodi = $request->prodi;

        if ($request->filled('password')) {
            $dosen->password = Hash::make($request->password);
        }

        $dosen->save();

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen '.$dosen->name.' berhasil diperbarui!');
    }

    public function destroyDosen($id)
    {
        $dosen = User::where('role', 'dosen')->findOrFail($id);

        // Hapus file foto fisik jika ada
        if ($dosen->foto && File::exists(public_path($dosen->foto))) {
            File::delete(public_path($dosen->foto));
        }

        $dosen->delete();

        return back()->with('success', 'Data Dosen berhasil dihapus!');
    }

    public function destroyMahasiswa($id)
    {
        $mahasiswa = User::where('role', 'mahasiswa')->findOrFail($id);

        // Hapus file foto fisik jika ada
        if ($mahasiswa->foto && File::exists(public_path($mahasiswa->foto))) {
            File::delete(public_path($mahasiswa->foto));
        }

        $mahasiswa->delete();

        return back()->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
