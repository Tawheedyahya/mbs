<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Medical booking system')</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{asset('css/manual.css')}}">
    <link rel="stylesheet"
      href="{{ asset('css/component.css') }}?v={{ filemtime(public_path('css/component.css')) }}">

    {{-- DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    {{-- END DATATABLES --}}
    @stack('styles')
</head>

<body>
<div class="d-flex flex-column min-vh-100">

    {{-- HEADER --}}
    @include('layouts.header')

    <div class="d-flex flex-fill flex-grow-1">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-fill p-4">
            <x-toast />
            @yield('content')
        </main>

    </div>

    {{-- FOOTER --}}
    @include('layouts.footer')

</div>


{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- JQUERY END --}}
{{-- DATATABLES --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
{{-- END DATATABLES --}}
@stack('scripts')
</body>
</html>
