<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">

    <header class="header">
        <div class="header__inner">
            COACHTECH
            <nav class="header__nav">
                <span>{{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </a>
            </nav>
        </div>
    </header>

    <main class="auth-main">
        <div class="auth-card">
            <h1 class="auth-title">マイページ</h1>

            <div class="profile-icon">
                @if(Auth::user()->profile_image_path)
                    <img class="icon-circle-img" src="{{ asset('storage/' . Auth::user()->profile_image_path) }}"
                        alt="profile">
                @else
                    <div class="icon-circle"></div>
                @endif
            </div>

            <p class="auth-text">
                ようこそ、{{ Auth::user()->name }} さん
            </p>

            <a class="auth-link" href="{{ route('profile.setup') }}">プロフィールを編集</a>
        </div>
    </main>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>

</body>

</html>