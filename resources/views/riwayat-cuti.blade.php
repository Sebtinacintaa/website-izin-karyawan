@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Cuti')

@section('content')
<div class="max-w-4xl mx-auto mr-4 mt-5 p-10 bg-white rounded-lg shadow mb-5 min-h-screen">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-[#4F3A2D]">Riwayat Pengajuan Cuti</h2>

        <!-- Tombol Filter dan Hapus Semua -->
        <div x-data="{ open: false, showModal: false }" x-cloak class="relative">
            <div class="flex gap-2">
                <!-- Tombol Filter -->
                <button @click="open = !open"
                        type="button"
                        class="bg-white font-medium text-blue-600 border border-blue-200 hover:text-blue-700 focus:ring-1 focus:ring-blue-500 px-4 py-2 rounded-lg flex items-center gap-2 transition-all">
                    <i class="fas fa-filter text-sm"></i> Filter
                </button>

                <!-- Tombol Hapus Semua -->
                <button type="button"
                        @click="showModal = true"
                        class="bg-white text-xl text-red-500 border border-red-200 hover:text-red-900 active:ring-red-300 rounded-lg flex items-center justify-center w-10 h-10 transition-all duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <!-- Overlay Filter -->
            <div x-show="open" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40"
                @click="open = false" style="display: none;"></div>

            <!-- Form Filter -->
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 top-[calc(100% + 8px)] mt-2 w-screen sm:w-96 bg-white border rounded-lg shadow-lg z-50 p-4 space-y-4">
                <form method="GET" action="{{ route('riwayat.pengajuan') }}" class="space-y-4">
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                        <select name="leave_type" id="leave_type"
                                class="mt-1 block w-full px-3 py-2 text-xs border border-gray-300 rounded-md bg-gray-100">
                            <option value="">Semua Jenis Cuti</option>
                            <option value="Cuti Tahunan" {{ request('leave_type') == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="Cuti Sakit" {{ request('leave_type') == 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                            <option value="Cuti Besar" {{ request('leave_type') == 'Cuti Besar' ? 'selected' : '' }}>Cuti Besar</option>
                            <option value="Izin" {{ request('leave_type') == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Lainnya" {{ request('leave_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700">Urutkan Berdasarkan</label>
                        <select name="sort_by" id="sort_by"
                                class="mt-1 block w-full px-3 py-2 text-xs border border-gray-300 rounded-md bg-gray-100">
                            <option value="desc" {{ request('sort_by') == 'desc' || !request('sort_by') ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm w-full">
                            Terapkan
                        </button>
                        <a href="{{ route('riwayat.pengajuan') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm w-full text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Modal Konfirmasi Hapus Semua -->
            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6">
                    <h2 class="text-lg font-semibold mb-3 text-gray-800">Konfirmasi Hapus Semua</h2>
                    <p class="text-sm text-gray-600 mb-5">Apakah Anda yakin ingin menghapus semua riwayat pengajuan cuti?</p>
                    <div class="flex justify-end gap-3">
                        <button @click="showModal = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">
                            Batal
                        </button>
                        <form action="{{ route('riwayat.clear') }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                Hapus Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Riwayat -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="text-xs uppercase bg-gradient-to-r from-[#D1C5BA] to-[#b89f83] text-[#5B4327] rounded-t-lg">
                <tr>
                    <th scope="col" class="px-6 py-4 rounded-tl-lg">Nama</th>
                    <th scope="col" class="px-6 py-4">Jenis Cuti</th>
                    <th scope="col" class="px-6 py-4">Tanggal</th>
                    <th scope="col" class="px-6 py-4">Durasi</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4">Aksi</th>
                    <th scope="col" class="px-6 py-4 rounded-tr-lg">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($leaves as $leave)
                    @php
                        $statusClass = match(strtolower($leave->status)) {
                            'approved' => 'bg-green-100 text-green-800 ring-1 ring-green-200',
                            'pending' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200',
                            'rejected' => 'bg-red-100 text-red-800 ring-1 ring-red-200',
                            default => 'bg-gray-100 text-gray-800 ring-1 ring-gray-200',
                        };
                        $statusIcon = match(strtolower($leave->status)) {
                            'approved' => 'fa-check-circle',
                            'pending' => 'fa-clock',
                            'rejected' => 'fa-times-circle',
                            default => 'fa-info-circle',
                        };
                    @endphp
                    <tr class="group hover:shadow-xs hover:bg-gray-50 transition-all duration-200">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $leave->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $leave->leave_type ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d F Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $leave->duration }} hari
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }} mr-1.5"></i>
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('leave.destroy', $leave->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('leave.show', $leave->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            Tidak ada riwayat pengajuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($leaves->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
