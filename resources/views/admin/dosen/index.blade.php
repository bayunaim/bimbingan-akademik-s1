<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Dosen') }}
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
                        <h3 class="text-lg font-bold text-indigo-800">Import Data Massal</h3>
                        <p class="text-sm text-indigo-600">Gunakan template yang disediakan untuk memasukkan banyak
                            dosen sekaligus.</p>
                    </div>
                    <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data"
                        class="flex items-center gap-3">
                        @csrf
                        <a href="{{ route('admin.dosen.template') }}"
                            class="text-sm font-semibold text-indigo-700 bg-white border border-indigo-300 px-4 py-2 rounded-lg hover:bg-indigo-100 transition">⬇️
                            Download Template</a>
                        <input type="file" name="file_excel" accept=".csv, .xlsx, .xls" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        <button type="submit"
                            class="bg-indigo-800 hover:bg-indigo-900 text-white font-bold py-2 px-6 rounded-lg text-sm transition shadow-sm">Upload</button>
                    </form>
                </div>

                <h3 class="text-lg font-bold text-indigo-700 mb-4 border-b pb-2">Tambah Dosen Baru</h3>

                {{-- WAJIB ADA enctype="multipart/form-data" AGAR BISA UPLOAD FOTO --}}
                <form action="{{ route('admin.dosen.store') }}" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIP / NIDN</label>
                        <input type="text" name="nomor_induk" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Akun (Untuk Login)</label>
                        <input type="email" name="email" required
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Program Studi (Prodi)</label>
                        <input type="text" name="prodi" placeholder="Contoh: Pendidikan Olahraga"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan = default (password123)"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*"
                            class="mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>

                    <div class="md:col-span-3 pt-2">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition">Simpan
                            Dosen</button>
                        <span class="text-xs text-gray-500 ml-3">*Jika password dikosongkan, otomatis menggunakan:
                            <b>password123</b></span>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                {{-- 🟢 TAMBAHKAN WRAPPER INI DI LUAR TABLE --}}
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Foto</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Nama Dosen
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">NIP/NIDN</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Prodi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($dosens as $dosen)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        @if ($dosen->foto && file_exists(public_path($dosen->foto)))
                                            <img src="{{ asset($dosen->foto) }}" alt="Foto"
                                                class="w-10 h-10 rounded-full object-cover border">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($dosen->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $dosen->name }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $dosen->nomor_induk ?? '-' }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $dosen->prodi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $dosen->email }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center space-x-2">
                                        <a href="{{ route('admin.dosen.edit', $dosen->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-sm font-semibold transition">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus data dosen ini?')">
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
                                        Belum ada data dosen yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- 🟢 TUTUP WRAPPER OVERFLOW --}}
            </div>

        </div>
    </div>
</x-app-layout>
