<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} | @yield('title')</title>


    @include('partials.styles')
    @livewireStyles
</head>

<body>
    <div class="page-wrapper">

        @include('partials.header')

        <main id="main">
            @yield('content')
        </main>

        @include('partials.footer')


        @include('partials.scripts')

        @stack('scripts')
    </div>
    @livewireScripts
</body>

</html>
