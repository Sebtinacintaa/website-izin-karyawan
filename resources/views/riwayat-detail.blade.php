@extends('layouts.app')

@section('content')
<main class="flex-1 ml-[250px] p-6 min-h-screen mb-3">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 px-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengajuan Cuti</h1>

            <!-- Tombol Unduh PDF -->
            <a href="{{ route('leave.download', $leave->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </a>
        </div>

        <!-- Informasi Pengguna -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->user->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">NIP</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->nip ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Jenis Cuti</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->leave_type ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Department</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->department ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Tanggal Cuti</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d F Y') }} –
                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d F Y') }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Durasi</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->duration }} hari</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Status</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @if ($leave->status == 'approved')
                        bg-green-100 text-green-800
                    @elseif ($leave->status == 'pending')
                        bg-yellow-100 text-yellow-800
                    @else
                        bg-red-100 text-red-800
                    @endif
                ">
                    <i class="fas fa-circle mr-2"></i> {{ ucfirst($leave->status) }}
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Nomor Telepon</label>
                <p class="mt-1 text-lg font-semibold text-gray-800">{{ $leave->phone_number ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Alasan Cuti -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-600">Alasan Cuti</label>
            <p class="mt-2 p-4 bg-gray-50 border border-gray-200 rounded-md text-gray-700">
                {{ $leave->reason ?? 'Tidak ada alasan yang diberikan.' }}
            </p>
        </div>

        <!-- Dokumen Pendukung -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-600">Dokumen Pendukung</label>
            @if ($leave->document)
                <div class="mt-2 flex items-center justify-between gap-2">
                    <span class="text-gray-700">{{ basename($leave->document) }}</span>
                    <a href="{{ asset('storage/' . $leave->document) }}" download
                       class="flex items-center gap-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i> Unduh Dokumen
                    </a>
                </div>
            @else
                <p class="text-gray-600">Tidak ada dokumen yang diunggah.</p>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-8 flex flex-col md:flex-row justify-between gap-3">
            <a href="{{ route('riwayat.pengajuan') }}"
               class="bg-gray-300 hover:bg-blue-100 text-gray-700 px-4 py-2 rounded-lg text-sm inline-flex items-center gap-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
            </a>

            @can('update', $leave)
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endcan
        </div>
    </div>
</main>
@endsection
