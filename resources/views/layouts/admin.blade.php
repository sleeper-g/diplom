@props([
    'title' => config('app.name', 'Идёмвкино'),
    'subtitle' => 'Администраторррская',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <header class="page-header">
        <div>
            <h1 class="page-header__title">
                {{ $title }}
            </h1>
            <span class="page-header__subtitle">{{ $subtitle }}</span>
        </div>
        <div class="admin-toolbar">
            <div class="admin-toolbar__user">
                <span class="admin-toolbar__user-name">{{ auth()->user()->name ?? 'Администратор' }}</span>
                <span class="admin-toolbar__user-role">роль: {{ auth()->user()->role ?? 'guest' }}</span>
            </div>
            <nav class="admin-toolbar__nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-toolbar__link {{ request()->routeIs('admin.dashboard') ? 'admin-toolbar__link_active' : '' }}">Панель</a>
                <a href="{{ route('admin.halls.index') }}" class="admin-toolbar__link {{ request()->routeIs('admin.halls.*') ? 'admin-toolbar__link_active' : '' }}">Залы</a>
                <a href="{{ route('admin.movies.index') }}" class="admin-toolbar__link {{ request()->routeIs('admin.movies.*') ? 'admin-toolbar__link_active' : '' }}">Фильмы</a>
                <a href="{{ route('admin.sessions.index') }}" class="admin-toolbar__link {{ request()->routeIs('admin.sessions.*') ? 'admin-toolbar__link_active' : '' }}">Сеансы</a>
                <a href="{{ route('admin.prices.index') }}" class="admin-toolbar__link {{ request()->routeIs('admin.prices.*') ? 'admin-toolbar__link_active' : '' }}">Цены</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-toolbar__logout">Выйти</button>
            </form>
        </div>
    </header>

    <main class="conf-steps">
        @if (session('success'))
            <div class="conf-step__alert conf-step__alert_success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="conf-step__alert conf-step__alert_error">
                <p class="conf-step__alert-title">Пожалуйста, исправьте ошибки:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script src="{{ asset('js/admin-accordion.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

