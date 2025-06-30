<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login')</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins :wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        beige: {
                            50: '#FAF7F2',
                            100: '#E8DFD3',
                            200: '#D3C1AA',
                            300: '#B89F83',
                        },
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="font-poppins bg-beige-50 min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>
</html>