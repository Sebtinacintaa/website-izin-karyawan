@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div x-data="{ overlayOpen: false, openDeleteModal: false, openDropdown: false }"
     class="flex-1 px-4 sm:px-2 lg:px-6 pb-5 pl-6 pr-6 ml-[250px] bg-beige-50 font-poppins min-h-screen">

    <div class="max-w-7xl bg-white p-6 rounded-lg shadow mx-auto w-full mt-6 mb-6 min-h-[500px] relative">

        <!-- Dropdown Titik Tiga -->
        <div class="absolute top-8 right-10 z-10">
            <div class="relative">
                <button @click="openDropdown = !openDropdown"
                        class="text-gray-600 hover:text-gray-800 focus:outline-none text-xl">&#8942;</button>
                <div x-show="openDropdown" @click.away="openDropdown = false" x-cloak
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-md z-20">
                    <button @click="openDeleteModal = true; openDropdown = false"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Hapus Akun
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="deleteAvatarInput" name="delete_avatar" value="0">

            <!-- Foto Profil -->
            <div>
                <label class="block font-semibold text-lg text-[#55433E] ml-5 mb-3">Foto Profil</label>
                <div class="flex flex-col md:flex-row items-center gap-4 ml-5">
                    <div class="relative">
                        <img id="profilePreview"
                             src="{{ auth()->user()->avatar
                                     ? asset('storage/' . auth()->user()->avatar) . '?t=' . now()->timestamp
                                     : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=B89F83&color=fff' }}"
                             alt="Profil"
                             class="user-avatar rounded-full border border-[#4C3623] w-24 h-24 object-cover" />
                        <button type="button"
                                onclick="document.getElementById('photoInput').click()"
                                class="absolute bottom-1 right-1 w-6 h-6 bg-white rounded-full shadow flex items-center justify-center">
                            <i class="fas fa-pencil text-[#6B4F3F] text-xs"></i>
                        </button>
                        <input type="file" id="photoInput" name="avatar" accept="image/*" class="hidden" onchange="handlePhotoUpload(event)" />
                    </div>
                    <button type="button" onclick="removePhoto()" class="flex items-center gap-2 px-4 py-2 text-sm bg-[#B04A4A] text-white rounded-md hover:bg-[#9b3e3e]">
                        <i class="fas fa-trash text-xs"></i> Hapus
                    </button>
                </div>
            </div>

            <!-- Form Input -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 m-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="far fa-eye text-gray-400"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <div class="relative">
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', optional($user->tanggal_lahir)->format('Y-m-d')) }}"
                               class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                        <span class="absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none">
                            <i class="far fa-calendar text-gray-400"></i>
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 mr-5">
                <button type="button" onclick="goToDashboard()" class="px-8 py-2.5 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-8 py-2.5 bg-[#8B6D5C] text-white rounded-lg hover:bg-[#7c614f]">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <div x-show="openDeleteModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         x-cloak>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Hapus Akun</h2>
            <p class="text-sm text-gray-600 mb-4">
                Apakah kamu yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label for="password" class="block text-sm text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password" required
                           class="mt-1 block w-full rounded bg-gray-200 border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="openDeleteModal = false"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sukses Simpan -->
<div id="save-success-overlay" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div id="saveSuccessModal"
        class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md text-center z-50">
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 m-2">Simpan Berhasil</h2>
        <p class="text-sm text-gray-600 mt-2 p-2" id="saveSuccessMessage">Profil berhasil diperbarui.</p>
        <button id="saveSuccessBtn" class="bg-blue-600 text-white px-1 py-2 w-full mt-1 rounded hover:bg-blue-700">OK</button>
    </div>
</div>

<script>window.csrfToken = "{{ csrf_token() }}";</script>
@push('after-scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush
@endsection