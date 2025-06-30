<!DOCTYPE html>
<html lang="en" x-data="{ overlayOpen: false }" x-cloak>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', config('app.name'))</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tippy.js -->
    <link href="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy.css" rel="stylesheet">

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Tailwind Custom Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        beige: {
                            50: '#FAF6F2',
                            100: '#E8DFD3',
                            200: '#B89F83',
                            300: '#8B7355',
                            500: '#B89F83',
                        },
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        };
    </script>

    <!-- Custom Animations & Cloak -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-out {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fade-out 0.3s ease-out;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body class="font-poppins bg-beige-50 min-h-screen flex">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main id="content-wrapper" class="flex-1 transition-all duration-300 ease-in-out p-4 ml-[50px]">
        <div class="container mx-auto mr-50 ml-[-30px]">
            @yield('content')
        </div>
    </main>

    <!-- Global Modal -->
   <div id="modal-overlay"
        x-show="overlayOpen"
        x-transition
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        @keydown.escape.window="overlayOpen = false"
        @click.self="overlayOpen = false"
        x-cloak>
v
        <div id="modal-content"
             class="bg-white rounded-lg shadow-lg max-w-md w-full mx-auto text-center px-6 py-8 relative animate-fade-in">
            <span @click="overlayOpen = false" class="absolute top-2 right-2 text-gray-500 cursor-pointer text-xl">&times;</span>
            <div id="modal-body">
                <p id="modal-message">Apakah Anda yakin ingin keluar dari akun?</p>
                <div id="modal-actions" class="mt-4 flex justify-end gap-3">
                    <button @click="overlayOpen = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button id="modal-confirm-button" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Toast Container -->
    <div id="toastContainer" class="fixed top-5 right-5 z-50 space-y-2"></div>

    <!-- Route JS Access -->
    <script>
        window.logoutUrl = "{{ route('logout') }}";
        window.loginUrl = "{{ route('login') }}";
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        window.csrfToken = csrfMeta ? csrfMeta.content : null;
    </script>

    <!-- External JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.170/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

    <!-- Local Scripts -->
    <script src="{{ asset('js/upload-handler.js') }}"></script>
    
    {{-- BARIS PENTING: Muat notification.js HANYA jika bukan di rute Filament --}}
    @if (! request()->routeIs('filament.*'))
        <script src="{{ asset('js/notification.js') }}"></script>
    @endif

    <script src="{{ asset('js/profile.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>

    <!-- Stack Scripts -->
    @stack('scripts')
    @stack('scripts-bottom')
    @stack('after-scripts')

</body>
</html>
