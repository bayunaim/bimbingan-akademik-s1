<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
            {{ __('Input Pengajuan Bimbingan Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6 md:p-8">

                @if(!auth()->user()->dosen_id)
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl relative shadow-sm flex items-center">
                        <span class="text-xl mr-3">⚠️</span>
                        <div>
                            <strong class="font-bold">Akses Terbatas!</strong>
                            <span class="block sm:inline text-sm">Anda belum memiliki Dosen Pembimbing. Silakan hubungi Kaprodi untuk plotting dosen sebelum mengirim pengajuan.</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-8">
                        <h3 class="text-lg font-extrabold text-primary-700 border-b-2 border-primary-100 pb-2 mb-5">Data Mahasiswa & Dosen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mahasiswa</label>
                                <input type="text" name="nama_mahasiswa" value="{{ auth()->user()->name }}" readonly class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-sm text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">NIM</label>
                                <input type="text" name="nim" value="{{ auth()->user()->nomor_induk }}" readonly class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-sm text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Semester</label>
                                <input type="text" name="semester" placeholder="Contoh: 7" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Akademik</label>
                                <input type="text" name="tahun_akademik" placeholder="Contoh: 2025/2026" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Dosen Pembimbing</label>
                                <input type="text" name="nama_dosen" value="{{ auth()->user()->dosenPembimbing->name ?? 'Belum Di-plot' }}" readonly class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-sm text-gray-600 cursor-not-allowed font-semibold">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Bimbingan</label>
                                <select name="jenis_bimbingan" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm font-medium">
                                    <option value="">-- Pilih Jenis Bimbingan --</option>
                                    <option value="Konsultasi Awal Perkuliahan">Konsultasi Awal Perkuliahan</option>
                                    <option value="Konsultasi Proses Perkuliahan">Konsultasi Proses Perkuliahan</option>
                                    <option value="Konsultasi Penyelesaian Akhir Kuliah">Konsultasi Penyelesaian Akhir Kuliah</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 p-5 bg-primary-50 rounded-xl border border-primary-100 shadow-sm">
                        <label class="block text-base font-extrabold text-primary-900 mb-2">Upload Draft Bimbingan (Wajib PDF)</label>
                        <p class="text-sm text-primary-600 mb-4">Silakan unggah dokumen draft / laporan yang ingin dikonsultasikan dengan dosen pembimbing.</p>
                        <input type="file" name="file_pdf" accept="application/pdf" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer transition">
                        <p class="text-xs text-primary-500 mt-2 font-medium">*Maksimal ukuran file: 5 MB.</p>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center border-b-2 border-primary-100 pb-2 mb-5">
                            <h3 class="text-lg font-extrabold text-primary-700">Rincian Kegiatan Konsultasi</h3>
                            <button type="button" id="tambah-baris" class="bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition">
                                + Tambah Baris
                            </button>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200" id="tabel-kegiatan">
                                <thead class="bg-primary-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider w-1/2">Kegiatan yang Dikonsultasikan</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-primary-800 uppercase tracking-wider w-1/2">Materi Bimbingan</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-primary-800 uppercase tracking-wider w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-3"><input type="text" name="kegiatan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500 placeholder-gray-400" placeholder="Contoh: Pengajuan Judul"></td>
                                        <td class="p-3"><input type="text" name="materi_bimbingan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500 placeholder-gray-400" placeholder="Contoh: Analisis Kinerja Sistem"></td>
                                        <td class="p-3 text-center">
                                            <button type="button" class="hapus-baris bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg px-3 py-1.5 text-sm font-bold transition shadow-sm">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('bimbingan.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl text-sm transition">Batal</a>
                        <button type="submit" class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm {{ !auth()->user()->dosen_id ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !auth()->user()->dosen_id ? 'disabled' : '' }}>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Script jQuery sesuai yang kamu request
        $('#tambah-baris').click(function() {
            var html = `<tr class="hover:bg-gray-50 transition">
                <td class="p-3"><input type="text" name="kegiatan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"></td>
                <td class="p-3"><input type="text" name="materi_bimbingan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"></td>
                <td class="p-3 text-center"><button type="button" class="hapus-baris bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg px-3 py-1.5 text-sm font-bold transition shadow-sm">X</button></td>
            </tr>`;
            $('#tabel-kegiatan tbody').append(html);
        });

        $(document).on('click', '.hapus-baris', function() {
            if($('#tabel-kegiatan tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    </script>
</x-app-layout>
