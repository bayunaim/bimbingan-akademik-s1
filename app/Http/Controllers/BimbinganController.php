<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bimbingan;

class BimbinganController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Siapkan query dasar
        $query = Bimbingan::latest();

        if ($user->role === 'mahasiswa') {
            // Kalau mahasiswa, cuma tampilkan data miliknya
            $query->where('nama_mahasiswa', $user->name);
        }
        elseif ($user->role === 'dosen') {
            // Kalau dosen, cuma tampilkan data yang dosen pembimbingnya adalah dia
            $query->where('nama_dosen', $user->name);
        }
        // Kalau 'admin', tidak ada filter tambahan (tampilkan semua)

        // Eksekusi query
        $datas = $query->get();

        return view('bimbingan.index', compact('datas'));
    }

    public function create()
    {
        // KUNCI: Hanya mahasiswa yang boleh buka halaman form
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak! Hanya Mahasiswa yang dapat membuat pengajuan.');
        }

        return view('bimbingan.create-bimbingan');
    }

    public function store(Request $request)
    {
        // 1. Kunci Keamanan: Hanya mahasiswa yang boleh nyimpan pengajuan
        if (auth()->user()->role !== 'mahasiswa') {
            abort(403, 'Akses Ditolak!');
        }

        $user = auth()->user();
        $dosen = $user->dosenPembimbing;

        // 2. Kunci Keamanan: Pastikan mahasiswa punya dosen pembimbing
        if (!$dosen) {
            abort(403, 'Anda belum memiliki Dosen Pembimbing. Silakan hubungi Kaprodi.');
        }

        // 3. Validasi PDF (Wajib PDF, Maksimal 5MB)
        $request->validate([
            'file_pdf' => 'required|mimes:pdf|max:5120',
        ]);

        // 4. Proses Upload File Langsung ke Folder Public
        $filePdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');
            // Bikin nama file unik pakai waktu
            $nama_file = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            // Pindahkan langsung ke public/berkas_bimbingan
            $file->move(public_path('berkas_bimbingan'), $nama_file);
            // Simpan nama foldernya untuk di database
            $filePdfPath = 'berkas_bimbingan/' . $nama_file;
        }

        // 5. Simpan Data Utama
        $bimbingan = Bimbingan::create([
            'nama_mahasiswa'      => $user->name,
            'nim'                 => $request->nim,
            'semester'            => $request->semester,
            'tahun_akademik'      => $request->tahun_akademik,
            'nama_dosen'          => $dosen->name,
            'nip_dosen'           => $dosen->nomor_induk ?? '-',
            'jenis_bimbingan'     => $request->jenis_bimbingan,
            'kegiatan_pengesahan' => $request->kegiatan_pengesahan,
            'jumlah_bimbingan'    => $request->jumlah_bimbingan,
            'catatan_umum'        => $request->catatan_umum,

            // Kolom baru untuk PDF dan Status
            'file_pdf'            => $filePdfPath,
            'status'              => 'Menunggu Review',
        ]);

        // 6. Simpan Data Rincian Kegiatan (Tabel bawah)
        if ($request->has('kegiatan')) {
            foreach ($request->kegiatan as $key => $kegiatan) {
                \App\Models\BimbinganDetail::create([
                    'bimbingan_id'     => $bimbingan->id,
                    'kegiatan'         => $kegiatan,
                    'materi_bimbingan' => $request->materi_bimbingan[$key],
                    'saran_solusi'     => $request->saran_solusi[$key] ?? null,
                    'keterangan'       => $request->keterangan[$key] ?? null,
                ]);
            }
        }

        return redirect()->route('bimbingan.index')->with('success', 'Pengajuan bimbingan dan berkas berhasil dikirim!');
    }

    public function cetak($id)
    {
        $bimbingan = Bimbingan::with('details')->findOrFail($id);
        $user = auth()->user();

        // KUNCI PRIVASI:
        // 1. Jika mahasiswa, nama di laporan harus sama dengan namanya
        if ($user->role === 'mahasiswa' && $bimbingan->nama_mahasiswa !== $user->name) {
            abort(403, 'Privasi: Anda tidak dapat melihat dokumen bimbingan mahasiswa lain.');
        }

        // 2. Jika dosen, nama dosen pembimbing di laporan harus sama dengan namanya
        if ($user->role === 'dosen' && $bimbingan->nama_dosen !== $user->name) {
            abort(403, 'Privasi: Anda tidak dapat melihat dokumen bimbingan dosen lain.');
        }

        return view('bimbingan.cetak-bimbingan', compact('bimbingan'));
    }

    // Menampilkan form review untuk Dosen
    public function edit($id)
    {
        // KUNCI: Cegah mahasiswa buka halaman review
        if (!in_array(auth()->user()->role, ['dosen', 'admin'])) {
            abort(403, 'Akses Ditolak! Hanya Dosen/Kaprodi yang dapat mereview.');
        }

        $bimbingan = Bimbingan::with('details')->findOrFail($id);

        // KUNCI EXTRA: Pastikan dosen cuma bisa review mahasiswa bimbingannya sendiri
        if (auth()->user()->role === 'dosen' && $bimbingan->nama_dosen !== auth()->user()->name) {
            abort(403, 'Akses Ditolak! Ini bukan mahasiswa bimbingan Anda.');
        }

        return view('bimbingan.edit', compact('bimbingan'));
    }

    // Memproses update data dari Dosen
    public function update(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['dosen', 'admin'])) {
            abort(403, 'Akses Ditolak!');
        }

        // Mengambil data bimbingan
        $bimbingan = Bimbingan::findOrFail($id);

        // Dosen mengupdate data pengesahan, catatan umum, DAN STATUS
        $bimbingan->update([
            'kegiatan_pengesahan' => $request->kegiatan_pengesahan,
            'jumlah_bimbingan'    => $request->jumlah_bimbingan,
            'catatan_umum'        => $request->catatan_umum,
            'status'              => $request->status ?? $bimbingan->status, // Mengambil status baru dari dosen
        ]);

        // Dosen mengupdate tabel rincian (saran & solusi)
        if ($request->has('detail_id')) {
            foreach ($request->detail_id as $key => $detail_id) {
                $detail = \App\Models\BimbinganDetail::find($detail_id);
                if ($detail) {
                    $detail->update([
                        'saran_solusi' => $request->saran_solusi[$key],
                        'keterangan'   => $request->keterangan[$key],
                    ]);
                }
            }
        }

        return redirect()->route('bimbingan.index')->with('success', 'Data bimbingan berhasil di-review dan status diperbarui!');
    }

    // --- FITUR KHUSUS MAHASISWA ---

    // 1. Menghapus Pengajuan
    public function destroy($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        // Kunci: Hanya mahasiswa pemilik data yang boleh hapus
        if (auth()->user()->role === 'mahasiswa' && $bimbingan->nama_mahasiswa !== auth()->user()->name) {
            abort(403, 'Akses Ditolak!');
        }

        // Cegah hapus kalau sudah disetujui dosen
        if ($bimbingan->status === 'Selesai') {
            return back()->with('error', 'Gagal: Data yang sudah di-ACC tidak bisa dihapus!');
        }

        // Hapus file fisik PDF dari folder public
        if ($bimbingan->file_pdf && file_exists(public_path($bimbingan->file_pdf))) {
            unlink(public_path($bimbingan->file_pdf));
        }

        $bimbingan->delete();
        return back()->with('success', 'Pengajuan bimbingan berhasil dihapus.');
    }

    // 2. Menampilkan Form Edit Mahasiswa
    public function editMahasiswa($id)
    {
        $bimbingan = Bimbingan::with('details')->findOrFail($id);

        if (auth()->user()->role !== 'mahasiswa' || $bimbingan->nama_mahasiswa !== auth()->user()->name) {
            abort(403, 'Akses Ditolak!');
        }

        if ($bimbingan->status === 'Selesai') {
            return back()->with('error', 'Data yang sudah di-ACC tidak bisa diedit lagi!');
        }

        return view('bimbingan.edit-mahasiswa', compact('bimbingan'));
    }

    // 3. Memproses Update dari Mahasiswa (Upload Ulang Revisi)
    public function updateMahasiswa(Request $request, $id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        if (auth()->user()->role !== 'mahasiswa' || $bimbingan->nama_mahasiswa !== auth()->user()->name) {
            abort(403, 'Akses Ditolak!');
        }

        // Upload PDF baru JIKA ADA yang diupload
        if ($request->hasFile('file_pdf')) {
            $request->validate(['file_pdf' => 'mimes:pdf|max:5120']);

            // Hapus PDF Lama
            if ($bimbingan->file_pdf && file_exists(public_path($bimbingan->file_pdf))) {
                unlink(public_path($bimbingan->file_pdf));
            }

            // Simpan PDF Baru
            $file = $request->file('file_pdf');
            $nama_file = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('berkas_bimbingan'), $nama_file);
            $bimbingan->file_pdf = 'berkas_bimbingan/' . $nama_file;
        }

        // Update Data Utama
        $bimbingan->jenis_bimbingan = $request->jenis_bimbingan;
        $bimbingan->status = 'Menunggu Review'; // 🟢 Otomatis kembali ke status Menunggu saat diedit
        $bimbingan->save();

        // Update rincian kegiatannya
        if ($request->has('detail_id')) {
            foreach ($request->detail_id as $key => $detail_id) {
                $detail = \App\Models\BimbinganDetail::find($detail_id);
                if ($detail) {
                    $detail->update([
                        'kegiatan' => $request->kegiatan[$key],
                        'materi_bimbingan' => $request->materi_bimbingan[$key],
                    ]);
                }
            }
        }

        return redirect()->route('bimbingan.index')->with('success', 'Data revisi pengajuan berhasil dikirim ulang ke Dosen!');
    }
}
