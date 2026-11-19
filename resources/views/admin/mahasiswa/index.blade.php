<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Mahasiswa & Plotting Dosen PA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-xl border border-gray-100">
                <div
                    class="bg-indigo-50 p-6 shadow-sm sm:rounded-xl border border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-indigo-800">Import Data & Plotting Massal</h3>
                        <p class="text-sm text-indigo-600">Gunakan template Excel untuk mendaftarkan mahasiswa sekaligus
                            memplot Dosen PA-nya via NIP.</p>
                    </div>
                    <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex items-center gap-3">
                        @csrf
                        <a href="{{ route('admin.mahasiswa.template') }}"
                            class="text-sm font-semibold text-indigo-700 bg-white border border-indigo-300 px-4 py-2 rounded-lg hover:bg-indigo-100 transition">⬇️
                            Download Template</a>
                        <input type="file" name="file_excel" accept=".csv, .xlsx, .xls" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        <button type="submit"
                            class="bg-indigo-800 hover:bg-indigo-900 text-white font-bold py-2 px-6 rounded-lg text-sm transition shadow-sm">Upload</button>
                    </form>
                </div>

                <h3 class="text-lg font-bold text-indigo-700 mb-4 border-b pb-2">Tambah Mahasiswa Baru</h3>

                <form action="{{ route('admin.mahasiswa.store') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIM (Nomor Induk)</label>
                        <input type="text" name="nomor_induk" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Akun</label>
                        <input type="email" name="email" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Program Studi (Prodi)</label>
                        <select name="prodi_id" id="prodi_id" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @if (auth()->user()->role === 'admin')
                                <option value="">-- Pilih Prodi --</option>
                            @endif
                            @foreach ($prodis as $p)
                                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dosen Pembimbing Akademik (PA)</label>
                        <select name="dosen_id"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">-- Pilih Dosen PA --</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" data-prodi="{{ $dosen->prodi_id }}">
                                    {{ $dosen->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan = default (password123)"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*"
                            class="mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>

                    <div class="md:col-span-3 pt-2">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition">Simpan
                            Mahasiswa</button>
                        <span class="text-xs text-gray-500 ml-3">*Jika password dikosongkan, otomatis menggunakan:
                            <b>password123</b></span>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                {{-- 🟢 TAMBAHKAN WRAPPER OVERFLOW DI SINI --}}
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Foto</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Mahasiswa
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Prodi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Dosen PA</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($mahasiswas as $mhs)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        @if ($mhs->foto && file_exists(public_path($mhs->foto)))
                                            <img src="{{ asset($mhs->foto) }}" alt="Foto"
                                                class="w-10 h-10 rounded-full object-cover border">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($mhs->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $mhs->name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $mhs->nomor_induk ?? '-' }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $mhs->prodi->kode ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-indigo-600 font-semibold">
                                        {{ $mhs->dosenPembimbing->name ?? 'Belum di-plot' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center space-x-2">
                                        <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-sm font-semibold transition">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-md text-sm font-semibold transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm font-medium">
                                        Belum ada data mahasiswa yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
