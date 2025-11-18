<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ИдёмВКино</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header class="page-header">
        <h1 class="page-header__title">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">Идём<span>в</span>кино</a>
        </h1>
        @auth
            <div style="position: absolute; top: 1.4rem; right: 1.4rem; color: #FFFFFF; font-size: 1.4rem;">
                <span>{{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="margin-left: 1rem; color: #FFFFFF; text-decoration: underline;">
                    Выход
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @else
            <div style="position: absolute; top: 1.4rem; right: 1.4rem; color: #FFFFFF; font-size: 1.4rem;">
                <a href="{{ route('login') }}" style="color: #FFFFFF; text-decoration: underline; margin-right: 1rem;">Вход</a>
                <a href="{{ route('register') }}" style="color: #FFFFFF; text-decoration: underline;">Регистрация</a>
            </div>
        @endauth
    </header>

    {{ $slot }}

    @stack('scripts')
</body>
</html>

