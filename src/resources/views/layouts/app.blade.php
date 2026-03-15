<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name'))</title>

    {{-- 共通CSS（全画面で読みたいもの） --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- 画面ごとのCSS --}}
    @stack('styles')
</head>

<body class="@yield('body_class')">
    @include('partials.header')

    <main class="@yield('main_class')">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>