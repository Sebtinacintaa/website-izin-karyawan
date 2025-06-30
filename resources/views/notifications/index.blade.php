@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto mr-4 mt-5 p-10 bg-white rounded-lg shadow mb-5 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-[#4F3A2D]">Notifikasi</h2>
        <button 
            id="clearNotificationsBtn"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-red-500 hover:text-white transition-colors duration-200 text-sm flex items-center">
            <i class="fas fa-trash-alt mr-2"></i> Clear Semua Notifikasi
        </button>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmClearModal" class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
            <h2 class="text-lg font-semibold mb-2">Konfirmasi</h2>
            <p class="mb-4">Apakah Anda yakin ingin menghapus semua notifikasi belum dibaca?</p>
            <div class="flex justify-end space-x-2">
                <button data-close-modal class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                <button id="confirm-clear-btn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>

    <!-- Info jumlah notifikasi -->
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
    @endphp
    <p class="text-sm text-gray-600 mb-4">
        Anda memiliki <strong>{{ $unreadCount }}</strong> notifikasi belum dibaca.
    </p>

    <!-- Tabs -->
    <div class="flex gap-4 mb-6 border-b pb-2">
        <button data-type="all" class="tab-btn font-medium border-b-2 border-transparent py-2 px-1 hover:text-blue-600 active" data-active="true">Semua</button>
        <button data-type="new" class="tab-btn font-medium border-b-2 border-transparent py-2 px-1 hover:text-blue-600">Baru</button>
        <button data-type="unread" class="tab-btn font-medium border-b-2 border-transparent py-2 px-1 hover:text-blue-600">Belum Dibaca</button>
    </div>

    <!-- Daftar Notifikasi -->
    <div id="notification-list" class="space-y-4" data-user-id="{{ auth()->user()->id }}">
        @forelse ($notifications as $notification)
            @php
                $title = strtolower($notification->data['title'] ?? '');
                $message = $notification->data['message'] ?? 'Tidak ada pesan.';
                $iconClass = 'fas fa-bell';
                $colorClass = 'bg-orange-100 text-orange-600';

                if (str_contains($title, 'approved')) {
                    $iconClass = 'fas fa-check-circle';
                    $colorClass = 'bg-green-100 text-green-600';
                } elseif (str_contains($title, 'rejected')) {
                    $iconClass = 'fas fa-times-circle';
                    $colorClass = 'bg-red-100 text-red-600';
                } elseif (str_contains($title, 'updated')) {
                    $iconClass = 'fas fa-hourglass-half';
                    $colorClass = 'bg-yellow-100 text-yellow-600';
                } elseif (str_contains($title, 'submitted')) {
                    $iconClass = 'fas fa-info-circle';
                    $colorClass = 'bg-blue-100 text-blue-600';
                }
            @endphp

            <div 
                class="notification-item flex items-start gap-4 p-4 {{ is_null($notification->read_at) ? 'bg-gray-50' : 'bg-green-50' }} hover:bg-gray-100 transition duration-200 rounded-lg shadow-sm border border-gray-200"
                data-read="{{ $notification->read_at ? 'true' : 'false' }}"
                data-id="{{ $notification->id }}">

                <!-- Icon -->
                <div class="w-10 h-10 flex items-center justify-center rounded-full mr-4 {{ $colorClass }}">
                    <i class="{{ $iconClass }} text-lg"></i>
                </div>

                <!-- Konten -->
                <div class="flex-1 ml-3">
                    <p class="font-semibold text-sm">{{ $notification->data['title'] ?? 'Tanpa Judul' }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $message }}</p>
                </div>

                <!-- Aksi -->
                <div class="flex flex-col items-end space-y-2">
                    @if (is_null($notification->read_at))
                        <span class="status-badge bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">Baru</span>
                    @else
                        <span class="status-badge bg-green-100 text-green-700 text-xs px-2 py-1 rounded">Sudah Dibaca</span>
                    @endif
                    <span class="text-xs text-gray-500 whitespace-nowrap">
                        {{ $notification->created_at->format('d/m/Y') }}
                    </span>
                    <button class="delete-notification text-red-500 hover:text-red-700" data-id="{{ $notification->id }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center mt-10 italic">Tidak ada notifikasi.</p>
        @endforelse

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.routes = {
        clear: "{{ route('notifications.clear') }}",
        read: "{{ route('notifications.read', ['id' => '__ID__']) }}".replace('%7E', '~'),
        delete: "{{ route('notifications.delete', ['id' => '__ID__']) }}",
        poll: "{{ route('notifications.poll', ['userId' => '__USER_ID__']) }}"
    };
</script>
<script src="{{ asset('js/notifications.js') }}" defer></script>
@endpush