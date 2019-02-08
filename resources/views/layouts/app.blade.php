<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    @foreach($locales as $locale => $lang)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ LaravelLocalization::getLocalizedURL($locale) }}">
    @endforeach

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('favicon-32x32.png') }}" style="height: 24px;" alt="{{ config('app.name') }}">
                <span class="text-monospace font-weight-bold" style="color: #E91E63;">{{ config('app.name') }}</span>
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/mikeyzm/eveningcore" target="_blank">
                        <i class="fab fa-github fa-fw"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="i18nDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-globe fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="i18nDropdown">
                        @foreach($locales as $locale => $lang)
                            <a class="dropdown-item {{ LaravelLocalization::getCurrentLocale() === $locale ? 'active' : '' }}"
                               href="{{ LaravelLocalization::getLocalizedURL($locale) }}"
                               rel="alternate"
                               hreflang="{{ $locale }}"
                            >
                                {{ $lang['native'] }}
                            </a>
                        @endforeach
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
