<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
            {{ __('Revisi Pengajuan Bimbingan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 p-6 md:p-8">

                @if($bimbingan->catatan_umum)
                <div class="mb-8 p-5 bg-rose-50 border border-rose-200 rounded-xl shadow-sm flex items-start">
                    <div class="text-2xl mr-4 mt-1">📝</div>
                    <div>
                        <h3 class="font-bold text-rose-800 mb-1">Catatan Revisi dari Dosen:</h3>
                        <p class="text-sm text-rose-700 italic">"{{ $bimbingan->catatan_umum }}"</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('bimbingan.mahasiswa.update', $bimbingan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-8 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-primary-700 mb-2">Jenis Bimbingan</label>
                                <select name="jenis_bimbingan" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm font-medium">
                                    <option value="Konsultasi Awal Perkuliahan" {{ $bimbingan->jenis_bimbingan == 'Konsultasi Awal Perkuliahan' ? 'selected' : '' }}>Konsultasi Awal Perkuliahan (KRS)</option>
                                    <option value="Konsultasi Tengah Semester" {{ $bimbingan->jenis_bimbingan == 'Konsultasi Tengah Semester' ? 'selected' : '' }}>Konsultasi Tengah Semester (Evaluasi UTS)</option>
                                    <option value="Konsultasi Akhir Semester" {{ $bimbingan->jenis_bimbingan == 'Konsultasi Akhir Semester' ? 'selected' : '' }}>Konsultasi Akhir Semester (Evaluasi UAS)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-primary-700 mb-2">Upload Revisi Draft (PDF)</label>
                                <p class="text-xs text-primary-600 mb-2">Abaikan jika tidak ada perubahan PDF. File saat ini: <a href="{{ asset($bimbingan->file_pdf) }}" target="_blank" class="font-bold underline text-primary-800 hover:text-primary-600">Lihat Berkas Lama</a></p>
                                <input type="file" name="file_pdf" accept="application/pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer transition">
                            </div>
                        </div>
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
                                    @foreach($bimbingan->details as $detail)
                                    <tr class="hover:bg-gray-50 transition">
                                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                                        <td class="p-3"><input type="text" name="kegiatan[]" value="{{ $detail->kegiatan }}" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"></td>
                                        <td class="p-3"><input type="text" name="materi_bimbingan[]" value="{{ $detail->materi_bimbingan }}" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"></td>
                                        <td class="p-3 text-center">
                                            <button type="button" class="hapus-baris bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg px-3 py-1.5 text-sm font-bold transition shadow-sm">X</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('bimbingan.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl text-sm transition">Batal</a>
                        <button type="submit" class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm">
                            Update & Kirim Ulang Pengajuan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#tambah-baris').click(function() {
            var html = `<tr class="hover:bg-gray-50 transition">
                <input type="hidden" name="detail_id[]" value="">
                <td class="p-3"><input type="text" name="kegiatan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ketik kegiatan baru..."></td>
                <td class="p-3"><input type="text" name="materi_bimbingan[]" required class="w-full rounded-md border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500" placeholder="Ketik materi baru..."></td>
                <td class="p-3 text-center"><button type="button" class="hapus-baris bg-rose-100 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg px-3 py-1.5 text-sm font-bold transition shadow-sm">X</button></td>
            </tr>`;
            $('#tabel-kegiatan tbody').append(html);
        });

        $(document).on('click', '.hapus-baris', function() {
            // Biar minimal sisa 1 baris di tabel
            if($('#tabel-kegiatan tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    </script>
</x-app-layout>
