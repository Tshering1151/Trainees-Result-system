<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('style')
</head>
<body>
    <header class="header">
        <img src="{{ asset('images/logo-left.png') }}" alt="Institute Logo" class="logo left">
        <div class="title">
            <h1>༄༅།། རྒྱལ་གཞུང་འཛིན་སྐྱོང་སློབ་སྡེ།</h1>
            <h1>ROYAL INSTITUTE OF MANAGEMENT</h1>
            <p>management for growth & development</p>
        </div>
        <img src="{{ asset('images/logo-right.png') }}" alt="Institute Logo" class="logo right">
    </header>
    
    <h2 class="system-title">@yield('header')

    @yield('content')
    @yield('script')

    

<footer class="bg-white rounded-lg shadow-sm dark:bg-gray-900 m-4">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="https://www.rim.edu.bt/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTORSokv71hmQeYlltqhGy8Ph_TG-LLmnGwjA&s" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">RIM</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">About</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Licensing</a>
                </li>
                <li>
                    <a href="#" class="hover:underline">Contact</a>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a href="https://www.rim.edu.bt/" class="hover:underline">RIM</a>
        . Royal Institute of Management | Designed by Tshering Nidup.</span>
    </div>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>