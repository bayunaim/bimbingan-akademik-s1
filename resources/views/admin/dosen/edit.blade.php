<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-xl border border-gray-100">

                <h3 class="text-lg font-bold text-indigo-700 mb-6 border-b pb-2">Form Edit Dosen</h3>

                {{-- WAJIB ADA enctype="multipart/form-data" AGAR UPLOAD FOTO BERHASIL --}}
                <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap & Gelar</label>
                            <input type="text" name="name" value="{{ old('name', $dosen->name) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP / NIDN</label>
                            <input type="text" name="nomor_induk"
                                value="{{ old('nomor_induk', $dosen->nomor_induk) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Akun (Untuk Login)</label>
                            <input type="email" name="email" value="{{ old('email', $dosen->email) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi (Prodi)</label>
                            <input type="text" name="prodi" value="{{ old('prodi', $dosen->prodi) }}"
                                placeholder="Contoh: Pendidikan Olahraga"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-[11px] text-gray-400 mt-1">Minimal 6 karakter jika diisi.</p>
                        </div>

                        <div
                            class="md:col-span-2 flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                @if ($dosen->foto && file_exists(public_path($dosen->foto)))
                                    <img src="{{ asset($dosen->foto) }}" alt="Foto Dosen"
                                        class="w-16 h-16 rounded-full object-cover border shadow-sm">
                                @else
                                    <div
                                        class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm">
                                        {{ strtoupper(substr($dosen->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto Profil</label>
                                <input type="file" name="foto" accept="image/*"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50 border border-gray-200 rounded-md">
                                <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.dosen') }}"
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
