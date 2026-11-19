<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
                {{ __('Data Bimbingan Akademik') }}
            </h2>

            @if (auth()->user()->role === 'mahasiswa')
                <a href="{{ route('bimbingan.create') }}"
                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2.5 px-5 rounded-xl shadow-md transition duration-200 transform hover:scale-105">
                    + Input Pengajuan Baru
                </a>
            @endif

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-semibold">✅ {{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl relative shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-semibold">❌ {{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">
                                    Tanggal</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">
                                    Mahasiswa</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-primary-800 uppercase tracking-wider">
                                    Jenis</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-primary-800 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-primary-800 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($datas as $row)
                                <tr class="hover:bg-primary-50/40 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                        {{ $row->created_at->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $row->nama_mahasiswa }}</div>
                                        <div class="text-xs text-gray-500">{{ $row->nim }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-800 font-semibold">{{ $row->jenis_bimbingan }}
                                        </div>
                                        <div class="text-xs text-primary-600 font-medium">{{ $row->nama_dosen }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($row->status === 'Menunggu Review')
                                            <span
                                                class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                                ⏳ Menunggu
                                            </span>
                                        @elseif($row->status === 'Revisi')
                                            <span
                                                class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200 shadow-sm">
                                                📝 Revisi
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                                ✅ Selesai (ACC)
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center space-x-2">

                                            @if (in_array(auth()->user()->role, ['dosen', 'kaprodi', 'admin']))
                                                <a href="{{ route('bimbingan.edit', $row->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white rounded-lg text-xs font-bold transition duration-200 border border-primary-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    Review
                                                </a>
                                            @endif

                                            @if (auth()->user()->role === 'mahasiswa' && $row->status !== 'Selesai')
                                                <a href="{{ route('bimbingan.mahasiswa.edit', $row->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white rounded-lg text-xs font-bold transition duration-200 border border-amber-200">
                                                    Edit
                                                </a>
                                                <form action="{{ route('bimbingan.destroy', $row->id) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-bold transition duration-200 border border-rose-200">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($row->status === 'Selesai')
                                                <a href="{{ route('bimbingan.cetak', $row->id) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-lg text-xs font-bold transition duration-200 border border-emerald-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                        </path>
                                                    </svg>
                                                    Cetak
                                                </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($datas->isEmpty())
                    <div class="text-center py-12 bg-gray-50/50">
                        <div class="text-4xl mb-3">🗂️</div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada data bimbingan.</p>
                        @if (auth()->user()->role === 'mahasiswa')
                            <p class="text-gray-400 text-xs mt-1">Klik tombol "+ Input Pengajuan Baru" di atas untuk
                                memulai.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
