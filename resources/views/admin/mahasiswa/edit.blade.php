<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data & Plotting Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-xl border border-gray-100">

                <h3 class="text-lg font-bold text-indigo-700 mb-6 border-b pb-2">Form Edit Mahasiswa</h3>

                <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $mahasiswa->name) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIM (Nomor Induk)</label>
                            <input type="text" name="nomor_induk"
                                value="{{ old('nomor_induk', $mahasiswa->nomor_induk) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Akun</label>
                            <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi (Prodi)</label>
                            <select name="prodi_id" id="prodi_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @if (auth()->user()->role === 'admin')
                                    <option value="">-- Pilih Prodi --</option>
                                @endif
                                @foreach ($prodis as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('prodi_id', $mahasiswa->prodi_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->kode }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing Akademik
                                (PA)</label>
                            <select name="dosen_id"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Dosen PA --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" data-prodi="{{ $dosen->prodi_id }}"
                                        {{ $mahasiswa->dosen_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div
                            class="md:col-span-2 flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                @if ($mahasiswa->foto && file_exists(public_path($mahasiswa->foto)))
                                    <img src="{{ asset($mahasiswa->foto) }}" alt="Foto Mahasiswa"
                                        class="w-16 h-16 rounded-full object-cover border shadow-sm">
                                @else
                                    <div
                                        class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm">
                                        {{ strtoupper(substr($mahasiswa->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto Profil</label>
                                <input type="file" name="foto" accept="image/*"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 border border-gray-200 rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.mahasiswa') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg transition text-sm">Batal</a>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition shadow-md">Simpan
                            Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        // Dosen PA hanya yang sejurusan dengan prodi mahasiswa
        (function() {
            const prodi = document.getElementById('prodi_id');
            const dosen = document.querySelector('select[name="dosen_id"]');
            if (!prodi || !dosen) return;

            function filter() {
                Array.from(dosen.options).forEach(function(o) {
                    if (!o.value) return;
                    const cocok = !prodi.value || o.dataset.prodi === prodi.value;
                    o.hidden = !cocok;
                    o.disabled = !cocok;
                    if (!cocok && o.selected) dosen.value = '';
                });
            }
            prodi.addEventListener('change', filter);
            filter();
        })();
    </script>
@endpush
