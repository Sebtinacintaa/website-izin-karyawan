@extends('layouts.guest')

@section('content')
<main class="bg-white rounded-3xl p-8 w-full max-w-md shadow-lg mx-auto">
    <header class="text-center mb-8">
        <h2 class="text-3xl font-bold text-[#906B4D] mb-2">Welcome back</h2>
        <p class="text-[#90756E] text-sm">Log in to your account</p>
    </header>

    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-4 text-green-600 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="space-y-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm text-[#B4A79C] mb-1">Email</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute right-3 top-1/2 -translate-y-1/2 text-[#4C3623] text-lg"></i>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           class="w-full px-3 py-2 text-[#906B4D] bg-transparent border-b border-gray-200 focus:border-beige-200 
                                  focus:outline-none transition-colors placeholder:text-[#B4A79C] placeholder:text-sm"/>
                </div>
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm text-[#B4A79C] mb-1">Password</label>
                <div class="relative">
                    <input id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full px-3 py-2 text-[#906B4D] bg-transparent border-b border-gray-200 focus:border-beige-200 
                                focus:outline-none transition-colors placeholder:text-[#B4A79C] placeholder:text-sm"/>

                    <!-- Icon toggle: default fa-lock -->
                    <i id="togglePassword"
                       class="fa-solid fa-lock absolute right-3 top-1/2 -translate-y-1/2 text-[#4C3623] text-lg cursor-pointer"></i>
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-[#4C3623] border-gray-300 rounded focus:ring-beige-200" />
                    <span class="ml-2 text-sm text-[#4C3623]">Remember me</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#906B4D] hover:underline">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-[#906B4D] font-semibold text-xl text-white py-3 rounded-lg hover:bg-[#b89f83] transition-colors mt-6">
                Log in
            </button>
        </div>
    </form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");

        toggle.addEventListener("click", function () {
            // Toggle type input
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";

            // Ganti ikon
            toggle.classList.toggle("fa-lock", !isPassword);
            toggle.classList.toggle("fa-lock-open", isPassword);
        });
    });
</script>
</main>
@endsection
    