<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Bimbingan Akademik</title>
    <style>
        /* Reset & Font Setup */
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; color: #000; line-height: 1.5; margin: 0; padding: 0; }
        * { box-sizing: border-box; }

        /* Layout Kertas A4 */
        .page { width: 210mm; min-height: 297mm; padding: 20mm; margin: 10mm auto; background: white; box-shadow: 0 0 5px rgba(0,0,0,0.1); }

        /* Header / Kop Surat */
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 25px; }
        .logo-kampus { width: 90px; margin-right: 20px; }
        .teks-kop { flex: 1; text-align: center; padding-right: 90px; /* Biar teks beneran di tengah walau ada logo di kiri */ }
        .teks-kop h4 { margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; }
        .teks-kop p { margin: 2px 0 0 0; font-size: 13px; }

        /* Judul Dokumen */
        .judul-dokumen { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; color: #145161; /* Disesuaikan dengan warna tema aplikasi */ }

        /* Tabel Info Mahasiswa */
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .info-table td:first-child { width: 220px; font-weight: bold; }
        .info-table td:nth-child(2) { width: 15px; }

        /* Tabel Grid (Rincian Kegiatan) */
        .grid-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grid-table th, .grid-table td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        .grid-table th { background-color: #f3f4f6; text-align: center; font-weight: bold; }
        .grid-table td:first-child { text-align: center; width: 40px; }

        /* Tanda Tangan */
        .ttd-container { display: flex; justify-content: flex-end; margin-top: 40px; }
        .ttd-box { width: 300px; text-align: center; }
        .ttd-box p { margin: 0 0 5px 0; }
        .ttd-space { height: 80px; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px !important; }

        /* Print Styles */
        .btn-print { display: block; width: 200px; margin: 20px auto; padding: 10px; background: #145161; color: white; text-align: center; text-decoration: none; font-family: sans-serif; border-radius: 5px; cursor: pointer; border: none; font-weight: bold;}
        .btn-print:hover { background: #0d3843; }

        @media print {
            body { background: white; }
            .page { margin: 0; box-shadow: none; padding: 0; }
            .btn-print { display: none; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen</button>

    <div class="page">
        <div class="kop-surat">
            <img src="https://via.placeholder.com/90?text=LOGO" alt="Logo Kampus" class="logo-kampus">
            <div class="teks-kop">
                <h4>PROGRAM STUDI PENDIDIKAN OLAHRAGA</h4>
                <p>SEKOLAH TINGGI OLAHRAGA DAN KESEHATAN (STOK) BINA GUNA<br>
                Alamat: Jl. Alumunium Raya No.77, Tj. Mulia, Kota Medan, Sumatera Utara<br>
                Website: https://stokbinaguna.ac.id/</p>
            </div>
        </div>

        <div class="judul-dokumen">KEGIATAN BIMBINGAN AKADEMIK MAHASISWA</div>

        <table class="info-table">
            <tr><td>NAMA MAHASISWA</td><td>:</td><td>{{ $bimbingan->nama_mahasiswa }}</td></tr>
            <tr><td>NIM</td><td>:</td><td>{{ $bimbingan->nim }}</td></tr>
            <tr><td>SEMESTER</td><td>:</td><td>{{ $bimbingan->semester }}</td></tr>
            <tr><td>TAHUN AKADEMIK</td><td>:</td><td>{{ $bimbingan->tahun_akademik }}</td></tr>
            <tr><td>NAMA DOSEN PEMBIMBING</td><td>:</td><td>{{ $bimbingan->nama_dosen }}</td></tr>
            <tr><td>NIP/NIDN</td><td>:</td><td>{{ $bimbingan->nip_dosen }}</td></tr>
            <tr><td>JENIS BIMBINGAN</td><td>:</td><td>{{ $bimbingan->jenis_bimbingan }}</td></tr>
        </table>

        <table class="grid-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KEGIATAN</th>
                    <th>MATERI BIMBINGAN</th>
                    <th>SARAN/SOLUSI</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingan->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->kegiatan }}</td>
                    <td>{{ $detail->materi_bimbingan }}</td>
                    <td>{{ $detail->saran_solusi }}</td>
                    <td>{{ $detail->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada rincian kegiatan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="ttd-container">
            <div class="ttd-box">
                <p>Medan, {{ date('d F Y') }}</p>
                <p>Dosen Pembimbing,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $bimbingan->nama_dosen }}</p>
                <p>NIP. {{ $bimbingan->nip_dosen }}</p>
            </div>
        </div>
    </div>

    <div class="page page-break">
        <div class="kop-surat">
            <img src="https://via.placeholder.com/90?text=LOGO" alt="Logo Kampus" class="logo-kampus">
            <div class="teks-kop">
                <h4>PROGRAM STUDI PENDIDIKAN OLAHRAGA</h4>
                <p>SEKOLAH TINGGI OLAHRAGA DAN KESEHATAN (STOK) BINA GUNA<br>
                Alamat: Jl. Alumunium Raya No.77, Tj. Mulia, Kota Medan, Sumatera Utara<br>
                Website: https://stokbinaguna.ac.id/</p>
            </div>
        </div>

        <div style="color: #145161; font-size: 16px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; text-align: center;">Lembar Pengesahan Bimbingan</div>
        <hr style="border: 0; border-bottom: 2px solid #000; margin-bottom: 30px;">

        <table class="info-table" style="margin-bottom: 40px;">
            <tr><td>Nama Dosen</td><td>:</td><td>{{ $bimbingan->nama_dosen }}</td></tr>
            <tr><td>Kegiatan Bimbingan</td><td>:</td><td>{{ $bimbingan->kegiatan_pengesahan ?? '-' }}</td></tr>
            <tr><td>Jumlah Bimbingan Akademik</td><td>:</td><td>{{ $bimbingan->jumlah_bimbingan ?? '-' }} Kali</td></tr>
            <tr><td>Catatan Umum</td><td>:</td><td>{{ $bimbingan->catatan_umum ?? '-' }}</td></tr>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 80px;">
            <div class="ttd-box">
                <p>Dosen Pembimbing Akademik</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $bimbingan->nama_dosen }}</p>
                <p>NIP. {{ $bimbingan->nip_dosen }}</p>
            </div>

            <div class="ttd-box">
                <p>Medan, {{ date('d F Y') }}</p>
                <p>Ketua Program Studi</p>
                <p>Pendidikan Olahraga</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">....................................................</p>
                <p>NIP. ........................................</p>
            </div>
        </div>
    </div>

</body>
</html>
