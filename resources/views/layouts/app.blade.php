<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href={{secure_asset('resources/css/app.css')}} rel="stylesheet"
    @livewireStyles
</head>
<body>
<div class="min-h-screen bg-gray-100">

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow border-b-2">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
@livewireScripts
@stack('scripts')
</body>
</html>