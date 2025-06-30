@extends('layouts.app')

@section('content')
<div id="content-wrapper" class="transition-all duration-300 ease-in-out md:ml-64 p-4">
    <div class="bg-[#B49C87] text-white rounded-xl p-4 flex justify-between items-center mb-4">
        <h1 class="text-xl font-normal m-2">Selamat pagi, {{ Auth::user()->name ?? 'Tamu' }}</h1>
        <a href="{{ route('profile.edit') }}" class="border border-white text-white text-sm px-4 py-2.5 rounded-full hover:bg-white hover:text-[#B49C87] transition">
            Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

        {{-- ========================================================== --}}
        {{-- ==== BAGIAN YANG DIPERBAIKI: Kalkulasi Sisa Kuota Cuti ==== --}}
        {{-- ========================================================== --}}
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col justify-center h-52">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-[#B49C87] text-2xl p-2"></i>
                    <h2 class="text-lg font-semibold text-[#4F3A2D]">Sisa Kuota Cuti Tahunan</h2>
                </div>
                <div class="text-center mt-2 mb-2">
                    @php
                        $totalLeaveQuota = 12;

                        // DIUBAH: Hanya jumlahkan 'total_hari' dari cuti yang statusnya 'approved'
                        // Ganti 'total_hari' dengan nama kolom durasi di database Anda (misal: duration, lama_cuti).
                        $usedLeaveDays = $leaves->where('status', 'approved')->sum('total_hari');

                        $remaining = max(0, $totalLeaveQuota - $usedLeaveDays);
                    @endphp
                    <p class="text-7xl font-extrabold text-[#B49C87]">{{ $remaining }}</p>
                    <p class="text-base text-[#4F3A2D] font-regular mt-4">
                        Anda memiliki <strong>{{ $remaining }}</strong> hari cuti tersisa.
                    </p>
                </div>
            </div>
        </div>
        {{-- ========================================================== --}}
        {{-- ====              AKHIR BAGIAN PERBAIKAN              ==== --}}
        {{-- ========================================================== --}}


        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex flex-col justify-center h-52">
            <div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-file-signature text-[#B49C87] text-2xl m-2"></i>
                    <h2 class="text-lg font-semibold text-[#4F3A2D]">Ajukan Cuti</h2>
                </div>
                <div class="text-center mt-4 mb-4">
                    <p class="text-base text-[#B49C87] mt-4 text-center font-regular">Ajukan cuti dengan mudah dan cepat!</p>
                </div>
            </div>
            <a href="{{ route('leave.request.create') }}"
            class="mt-5 bg-[#B49C87] text-white py-2 text-sm rounded-lg w-full text-center hover:bg-[#a08772] transition">
                Ajukan Sekarang
            </a>
        </div>
    </div>

    <div class="bg-white p-4 pb-10 rounded-xl shadow-sm border border-gray-100 mt-6 min-h-[300px] relative">
        <div class="flex justify-between items-center mb-4 pb-3">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#B49C87]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6M4 6h16M10 11h4" />
                </svg>
                <h2 class="text-lg font-semibold text-[#4F3A2D]">Riwayat Pengajuan Cuti</h2>
            </div>
            <a href="{{ route('riwayat.pengajuan') }}" class="text-sm text-[#B49C87] border border-[#B49C87] px-4 py-2 mr-5 rounded-full hover:bg-[#B49C87] hover:text-white transition">
                Lihat Semua
            </a>
        </div>
        @if (isset($leaves) && $leaves->isNotEmpty())
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600 border-b px-5">
                        <th class="pb-3 font-medium">Nama</th>
                        <th class="pb-3 font-medium">Tanggal</th>
                        <th class="pb-3 font-medium">Jenis Cuti</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        @php
                            $statusClass = match(strtolower($leave->status)) {
                                'approved' => 'text-green-700',
                                'pending' => 'text-yellow-700',
                                'rejected' => 'text-red-700',
                                default => 'text-gray-700'
                            };

                            $statusIcon = match(strtolower($leave->status)) {
                                'approved' => 'fa-check-circle',
                                'pending' => 'fa-clock',
                                'rejected' => 'fa-times-circle',
                                default => 'fa-info-circle'
                            };
                        @endphp

                        <tr class="border-t text-gray-700">
                            <td class="py-3">{{ $leave->user->name ?? 'N/A' }}</td>
                            <td class="py-3">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</td>
                            <td class="py-3">{{ $leave->leave_type }}</td>
                            <td class="py-3">
                                <span class="inline-flex items-center gap-1 {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i>
                                    <span>{{ ucfirst($leave->status) }}</span>
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="{{ route('leave.detail', $leave->id) }}" class="text-[#B49C87]">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-base text-gray-500 italic absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">Belum ada riwayat pengajuan cuti.</p>
        @endif
    </div>
</div>
@endsection