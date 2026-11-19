<x-app-layout>
    <x-slot name="header">
        Review Data Bimbingan
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6 md:p-8">

                <form action="{{ route('bimbingan.update', $bimbingan->id) }}" method="POST">
                    @csrf

                    <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><span class="font-semibold text-gray-500">Nama Mahasiswa:</span> <span class="font-bold text-gray-800">{{ $bimbingan->nama_mahasiswa }}</span></div>
                            <div><span class="font-semibold text-gray-500">NIM:</span> <span class="font-bold text-gray-800">{{ $bimbingan->nim }}</span></div>
                            <div><span class="font-semibold text-gray-500">Semester/TA:</span> <span class="font-bold text-gray-800">{{ $bimbingan->semester }} / {{ $bimbingan->tahun_akademik }}</span></div>
                            <div><span class="font-semibold text-gray-500">Jenis Bimbingan:</span> <span class="font-bold text-gray-800">{{ $bimbingan->jenis_bimbingan }}</span></div>
                        </div>
                    </div>

                    @if($bimbingan->file_pdf)
                        <div class="mb-8 p-4 bg-primary-50 rounded-xl border border-primary-100 flex items-center justify-between shadow-sm">
                            <div>
                                <h4 class="font-bold text-primary-900">Berkas Draft Mahasiswa</h4>
                                <p class="text-xs text-primary-600 mt-1">Silakan unduh atau lihat berkas PDF yang dilampirkan mahasiswa sebelum mereview.</p>
                            </div>
                            <a href="{{ asset($bimbingan->file_pdf) }}" target="_blank" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg transition shadow-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Lihat PDF
                            </a>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-extrabold text-primary-700 border-b-2 border-primary-100 pb-2 mb-5">Form Penilaian Dosen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kegiatan Bimbingan</label>
                                <input type="text" name="kegiatan_pengesahan" value="{{ $bimbingan->kegiatan_pengesahan }}" placeholder="Contoh: Pengisian KRS" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Bimbingan (Berapa Kali)</label>
                                <input type="number" name="jumlah_bimbingan" value="{{ $bimbingan->jumlah_bimbingan }}" placeholder="Contoh: 3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Umum Dosen</label>
                            <textarea name="catatan_umum" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Tuliskan evaluasi keseluruhan di sini...">{{ $bimbingan->catatan_umum }}</textarea>
                        </div>

                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <label class="block text-sm font-extrabold text-amber-900 mb-2">Ubah Status Bimbingan</label>
                            <select name="status" required class="w-full md:w-1/2 rounded-md border-amber-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm font-semibold text-gray-700">
                                <option value="Menunggu Review" {{ $bimbingan->status == 'Menunggu Review' ? 'selected' : '' }}>⏳ Menunggu Review</option>
                                <option value="Revisi" {{ $bimbingan->status == 'Revisi' ? 'selected' : '' }}>📝 Perlu Revisi</option>
                                <option value="Selesai" {{ $bimbingan->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai (ACC)</option>
                            </select>
                            <p class="text-xs text-amber-700 mt-2">*Pilih "Selesai (ACC)" jika mahasiswa sudah bisa mencetak lembar pengesahan.</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-extrabold text-primary-700 border-b-2 border-primary-100 pb-2 mb-5">Rincian Saran & Solusi</h3>
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-primary-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">Kegiatan (Dari Mhs)</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">Materi (Dari Mhs)</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">Isi Saran / Solusi</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($bimbingan->details as $detail)
                                    <tr class="hover:bg-gray-50 transition">
                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                                        <td class="p-4 text-sm text-gray-600 bg-gray-50/50 w-1/4 align-top">{{ $detail->kegiatan }}</td>
                                        <td class="p-4 text-sm text-gray-600 bg-gray-50/50 w-1/4 align-top">{{ $detail->materi_bimbingan }}</td>

                                        <td class="p-3 w-1/4 align-top"><textarea name="saran_solusi[]" rows="2" class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500 placeholder-gray-400" placeholder="Ketik saran...">{{ $detail->saran_solusi }}</textarea></td>
                                        <td class="p-3 w-1/4 align-top"><input type="text" name="keterangan[]" value="{{ $detail->keterangan }}" placeholder="Ket..." class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 flex justify-end gap-3">
                        <a href="{{ route('bimbingan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 px-6 rounded-xl transition duration-200 text-sm">Batal</a>
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm">
                            Simpan Hasil Review
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
