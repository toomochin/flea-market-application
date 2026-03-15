<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>メール認証</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">

    <header class="header">
        <div class="header__inner">COACHTECH</div>
    </header>

    <main class="auth-main">
        <div class="auth-card">
            <h1 class="auth-title">メール認証</h1>

            <p class="auth-text">
                登録したメールアドレスに認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="auth-success">認証メールを再送しました。</p>
            @endif

            {{-- 要件：認証はこちらから --}}
            <a href="{{ route('verification.notice') }}" class="btn btn-black">
                認証はこちらから
            </a>

            {{-- 要件：認証メール再送 --}}
            <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 12px;">
                @csrf
                <button type="submit" class="btn btn-gray">
                    認証メールを再送する
                </button>
            </form>

            {{-- ログアウト --}}
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 18px;">
                @csrf
                <button type="submit" class="auth-link-btn">
                    ログアウト
                </button>
            </form>
        </div>
    </main>

</body>

</html>