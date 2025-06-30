<aside id="sidebar"
    class="fixed top-0 left-0 h-screen w-64 bg-white shadow-lg p-4 z-10 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- Sidebar Header -->
    <div class="flex items-center gap-2 mb-6">
        <h1 class="text-[#906B4D] text-2xl font-bold flex-1 text-center sidebar-text">Dashboard</h1> 
    </div>

    <!-- Search Bar -->
    <div class="relative mb-6">
        <label for="searchInput" class="sr-only">Search</label>
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
            <i class="fas fa-search text-sm"></i>
        </span>
        <input id="searchInput" type="text" placeholder="Search..."
            class="w-full pl-10 pr-4 py-2 bg-[#FAF5F2] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#B89F83]" />   
    </div>

    <!-- Navigation Links -->
    <div class="space-y-1 mb-6">
        <p class="text-xs text-gray-400 mb-2 sidebar-text">Navigation</p>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-500 hover:text-[#906B4D] hover:bg-beige-50 transition-colors menu-item">
            <i class="fas fa-home w-5 h-5"></i>
            <span class="text-sm sidebar-text">Home</span>
        </a>
        <a href="{{ url('/pengajuan-surat') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-500 hover:text-[#906B4D] hover:bg-beige-50 transition-colors menu-item">
            <i class="fas fa-pen-square w-5 h-5"></i>
            <span class="text-sm sidebar-text">Pengajuan Surat</span>
        </a>
        <a href="{{ url('/riwayat') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-500 hover:text-[#906B4D] hover:bg-beige-50 transition-colors menu-item">
            <i class="fas fa-file-alt w-5 h-5"></i>
            <span class="text-sm sidebar-text">Riwayat Pengajuan</span>
        </a>
        <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-500 hover:text-[#906B4D] hover:bg-beige-50 transition-colors menu-item">
            <i class="fas fa-bell w-5 h-5"></i>
            <span class="text-sm sidebar-text">Notifikasi</span>
        </a>
    </div>

    <!-- Account Section -->
    <div class="space-y-1 mb-6 absolute left-4 right-4">
        <p class="text-xs text-gray-400 mb-2 sidebar-text">Account</p>
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 rounded-lg text-gray-500 hover:text-[#906B4D] hover:bg-beige-50 transition-colors menu-item">
            <i class="fas fa-user w-5 h-5"></i>
            <span class="text-sm sidebar-text">Profile</span>
        </a>
    </div>

    <!-- Logout Button -->
    <div class="absolute bottom-4 left-4 right-4">
        @php
            $user = auth()->user();
            $avatarUrl = $user && $user->avatar && Storage::exists('public/' . $user->avatar)
                ? asset('storage/' . $user->avatar) . '?t=' . now()->timestamp
                : 'https://ui-avatars.com/api/?name=' . urlencode($user?->name ?? 'Guest') . '&background=B89F83&color=fff';
        @endphp

        <button type="button" onclick="logout()" class="flex items-center justify-between w-full p-2 bg-[#E1D5CA] rounded-lg hover:bg-[#D7C4B5] focus:outline-none focus:ring-2 focus:ring-[#8B7355] transition-all duration-300">
            <div class="flex items-center gap-3">
                <img src="{{ $avatarUrl }}" alt="Avatar" class="user-avatar w-10 h-10 rounded-full object-cover">
                <span class="text-sm text-gray-700">{{ $user->name }}</span>
            </div>
            <i class="fas fa-arrow-right-from-bracket text-xl text-gray-500 hover:text-[#906B4D]"></i>
        </button>
    </div>

</aside>

@section('scripts')
    <script>
        window.logoutUrl = "{{ route('logout') }}";
    </script>
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
