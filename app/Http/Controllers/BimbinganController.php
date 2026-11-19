<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\BimbinganDetail;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    /** Ambil bimbingan hanya jika boleh dilihat user yang login, selain itu 404 */
    private function findVisible($id, bool $withDetails = false): Bimbingan
    {
        $q = Bimbingan::visibleTo(auth()->user());
        if ($withDetails) {
            $q->with('details');
        }
        return $q->findOrFail($id);
    }

    private function isReviewer(): bool
    {
        return in_array(auth()->user()->role, ['dosen', 'kaprodi', 'admin']);
    }

    private function simpanPdf(Request $request): string
    {
        $file = $request->file('file_pdf');
        $nama = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(public_path('berkas_bimbingan'), $nama);
        return 'berkas_bimbingan/' . $nama;
    }

    private function hapusPdf(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    public function index()
    {
        $datas = Bimbingan::visibleTo(auth()->user())->with('prodi')->latest()->get();
        return view('bimbingan.index', compact('datas'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak! Hanya Mahasiswa yang dapat membuat pengajuan.');
        }
        return view('bimbingan.create-bimbingan');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak!');
        }

        $dosen = $user->dosenPembimbing;
        if (!$dosen) {
            abort(403, 'Anda belum memiliki Dosen Pembimbing. Silakan hubungi Kaprodi.');
        }

        $request->validate([
            'file_pdf'        => 'required|mimes:pdf|max:5120',
            'semester'        => 'required|string|max:10',
            'tahun_akademik'  => 'required|string|max:20',
            'jenis_bimbingan' => 'required|string|max:255',
        ]);

        $bimbingan = Bimbingan::create([
            'mahasiswa_id'        => $user->id,
            'dosen_id'            => $dosen->id,
            'prodi_id'            => $user->prodi_id,
            // snapshot untuk dokumen cetak (nama/NIM tidak dipakai untuk akses data)
            'nama_mahasiswa'      => $user->name,
            'nim'                 => $user->nomor_induk,
            'semester'            => $request->semester,
            'tahun_akademik'      => $request->tahun_akademik,
            'nama_dosen'          => $dosen->name,
            'nip_dosen'           => $dosen->nomor_induk ?? '-',
            'jenis_bimbingan'     => $request->jenis_bimbingan,
            'kegiatan_pengesahan' => $request->kegiatan_pengesahan,
            'jumlah_bimbingan'    => $request->jumlah_bimbingan ?? 0,
            'catatan_umum'        => $request->catatan_umum,
            'file_pdf'            => $this->simpanPdf($request),
            'status'              => 'Menunggu Review',
        ]);

        foreach ((array) $request->kegiatan as $key => $kegiatan) {
            BimbinganDetail::create([
                'bimbingan_id'     => $bimbingan->id,
                'kegiatan'         => $kegiatan,
                'materi_bimbingan' => $request->materi_bimbingan[$key] ?? '',
                'saran_solusi'     => $request->saran_solusi[$key] ?? null,
                'keterangan'       => $request->keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('bimbingan.index')->with('success', 'Pengajuan bimbingan dan berkas berhasil dikirim!');
    }

    public function cetak($id)
    {
        $bimbingan = $this->findVisible($id, true);
        $bimbingan->load('prodi');
        return view('bimbingan.cetak-bimbingan', compact('bimbingan'));
    }

    // Form review untuk Dosen / Kaprodi / Admin
    public function edit($id)
    {
        if (!$this->isReviewer()) {
            abort(403, 'Akses Ditolak! Hanya Dosen/Kaprodi yang dapat mereview.');
        }
        $bimbingan = $this->findVisible($id, true);
        return view('bimbingan.edit', compact('bimbingan'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->isReviewer()) {
            abort(403, 'Akses Ditolak!');
        }
        // findVisible memastikan dosen hanya bisa mengubah bimbingan mahasiswanya sendiri
        $bimbingan = $this->findVisible($id);

        $request->validate(['status' => 'nullable|in:Menunggu Review,Revisi,Selesai']);

        $bimbingan->update([
            'kegiatan_pengesahan' => $request->kegiatan_pengesahan,
            'jumlah_bimbingan'    => $request->jumlah_bimbingan,
            'catatan_umum'        => $request->catatan_umum,
            'status'              => $request->status ?? $bimbingan->status,
        ]);

        foreach ((array) $request->detail_id as $key => $detailId) {
            // hanya detail milik bimbingan ini
            $detail = $bimbingan->details()->find($detailId);
            if ($detail) {
                $detail->update([
                    'saran_solusi' => $request->saran_solusi[$key] ?? null,
                    'keterangan'   => $request->keterangan[$key] ?? null,
                ]);
            }
        }

        return redirect()->route('bimbingan.index')->with('success', 'Data bimbingan berhasil di-review dan status diperbarui!');
    }

    // --- FITUR KHUSUS MAHASISWA ---

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak! Hanya mahasiswa pemilik yang dapat menghapus pengajuan.');
        }

        $bimbingan = $this->findVisible($id);

        if ($bimbingan->status === 'Selesai') {
            return back()->with('error', 'Gagal: Data yang sudah di-ACC tidak bisa dihapus!');
        }

        $this->hapusPdf($bimbingan->file_pdf);
        $bimbingan->delete();

        return back()->with('success', 'Pengajuan bimbingan berhasil dihapus.');
    }

    public function editMahasiswa($id)
    {
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak!');
        }
        $bimbingan = $this->findVisible($id, true);

        if ($bimbingan->status === 'Selesai') {
            return back()->with('error', 'Data yang sudah di-ACC tidak bisa diedit lagi!');
        }
        return view('bimbingan.edit-mahasiswa', compact('bimbingan'));
    }

    public function updateMahasiswa(Request $request, $id)
    {
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak!');
        }
        $bimbingan = $this->findVisible($id);

        if ($bimbingan->status === 'Selesai') {
            return back()->with('error', 'Data yang sudah di-ACC tidak bisa diedit lagi!');
        }

        if ($request->hasFile('file_pdf')) {
            $request->validate(['file_pdf' => 'mimes:pdf|max:5120']);
            $this->hapusPdf($bimbingan->file_pdf);
            $bimbingan->file_pdf = $this->simpanPdf($request);
        }

        $bimbingan->jenis_bimbingan = $request->jenis_bimbingan;
        $bimbingan->status = 'Menunggu Review';
        $bimbingan->save();

        foreach ((array) $request->detail_id as $key => $detailId) {
            $detail = $bimbingan->details()->find($detailId);
            if ($detail) {
                $detail->update([
                    'kegiatan'         => $request->kegiatan[$key],
                    'materi_bimbingan' => $request->materi_bimbingan[$key],
                ]);
            }
        }

        return redirect()->route('bimbingan.index')->with('success', 'Data revisi pengajuan berhasil dikirim ulang ke Dosen!');
    }
}
