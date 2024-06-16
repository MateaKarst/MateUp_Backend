<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-100">
    <header>
        <nav class="bg-gray-800 p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <a href="/" class="text-white text-2xl font-semibold">Back</a>
            </div>
        </nav>
    </header>

    <main class="mt-8">
        <div class="max-w-7xl mx-auto px-6">
            @yield('content')
        </div>
    </main>

    <footer class="bg-gray-800 mt-8 py-6">
        <div class="max-w-7xl mx-auto px-6 text-white text-center">
            &copy; 2024 Your App. All rights reserved.
        </div>
    </footer>
</body>
</html>
