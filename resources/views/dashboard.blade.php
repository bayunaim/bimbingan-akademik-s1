<x-app-layout>
    <x-slot name="header">
        Dashboard Utama
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div
                class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between bg-gradient-to-r from-white to-primary-50/50">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 bg-primary-100 p-4 rounded-full text-3xl shadow-inner hidden sm:block">
                        👋
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-extrabold text-gray-800 tracking-tight">Selamat datang,
                            {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-500 mt-1">Sistem Informasi Bimbingan Akademik STOK Binaguna. Anda masuk
                            sebagai <span
                                class="font-bold text-primary-600 uppercase px-2 py-0.5 bg-primary-50 rounded">{{ Auth::user()->role }}</span>.
                        </p>

                        @if (Auth::user()->role === 'mahasiswa')
                            <div
                                class="mt-4 inline-flex items-center px-4 py-2.5 bg-white border border-primary-100 rounded-xl shadow-sm">
                                <span class="text-2xl mr-3">👨‍🏫</span>
                                <div>
                                    <p class="text-[10px] text-primary-500 font-bold uppercase tracking-wider">Dosen
                                        Pembimbing Akademik</p>
                                    <p class="text-sm font-extrabold text-primary-900">
                                        {{ Auth::user()->dosenPembimbing ? Auth::user()->dosenPembimbing->name : 'Belum di-plot oleh Kaprodi' }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-right hidden lg:block">
                    <p class="text-sm font-semibold text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @if (Auth::user()->role === 'admin')
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
                    <div class="p-4 bg-blue-50 text-blue-600 rounded-xl mr-5"><svg class="w-8 h-8" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg></div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Dosen</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $totalDosen }}</p>
                    </div>
                </div>
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl mr-5"><svg class="w-8 h-8" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                            </path>
                        </svg></div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Mahasiswa</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $totalMahasiswa }}</p>
                    </div>
                </div>
            @else
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
                    <div class="p-4 bg-amber-50 text-amber-500 rounded-xl mr-5"><svg class="w-8 h-8" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Menunggu Review</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $menungguReview }}</p>
                    </div>
                </div>
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
                    <div class="p-4 bg-emerald-50 text-emerald-500 rounded-xl mr-5"><svg class="w-8 h-8" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Selesai Review</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $selesaiReview }}</p>
                    </div>
                </div>
            @endif

            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition">
                <div class="p-4 bg-primary-50 text-primary-600 rounded-xl mr-5"><svg class="w-8 h-8" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg></div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wider">Total Dokumen</p>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalBimbingan }}</p>
                </div>
            </div>

        </div>

        {{-- TABEL KHUSUS DOSEN: DAFTAR MAHASISWA BIMBINGAN --}}
        @if (Auth::user()->role === 'dosen')
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Mahasiswa Bimbingan Saya</h3>
                    <span class="px-3 py-1 text-xs font-bold bg-primary-50 text-primary-600 rounded-full">Total:
                        {{ count($mahasiswas) }} Mahasiswa</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    Nama Mahasiswa</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    NIM</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    Email</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($mahasiswas as $index => $mhs)
                                <tr class="hover:bg-primary-50/30 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                        {{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                        {{ $mhs->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $mhs->nomor_induk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $mhs->email }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 font-medium text-sm">
                                        Belum ada mahasiswa yang di-plot ke Anda oleh Kaprodi/Admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TABEL AKTIVITAS BIMBINGAN TERBARU --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Aktivitas Bimbingan Terbaru</h3>
                <a href="{{ route('bimbingan.index') }}"
                    class="text-sm font-semibold text-primary-600 hover:text-primary-800">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Mahasiswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Dosen</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($bimbinganTerbaru as $row)
                            <tr class="hover:bg-primary-50/30 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                    {{ $row->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                    {{ $row->nama_mahasiswa }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $row->nama_dosen }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($row->status == 'Menunggu Review')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-amber-100 text-amber-700">⏳
                                            Menunggu</span>
                                    @elseif($row->status == 'Revisi')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-rose-100 text-rose-700">📝
                                            Revisi</span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">✅
                                            ACC</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 font-medium text-sm">
                                    Belum ada aktivitas bimbingan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
