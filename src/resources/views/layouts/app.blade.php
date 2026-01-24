<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}?v={{ filemtime(public_path('css/common.css')) }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&family=Noto+Serif+JP:wght@400;700&display=swap"
        rel="stylesheet">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                Contact Form
            </a>
            {{-- ログインしている時だけ表示 --}}
            @auth
                <div class="header__nav">
                    <a class="header__link" href="{{ route('admin.index') }}">Admin</a>

                    <form method="POST" action="{{ route('logout') }}" class="header__logout">
                        @csrf
                        <button type="submit" class="header__button">ログアウト</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>