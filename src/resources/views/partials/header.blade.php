<header class="header">
    <div class="header-inner">
        <a href="{{ route('items.index') }}" class="header-logo">
            <img src="{{ asset('images/logo/coachtech-logo.png') }}" alt="COACHTECH">
        </a>

        <div class="header-search">
            <form action="{{ route('items.index') }}" method="GET">
                <input type="hidden" name="tab" value="{{ $tab ?? 'all' }}">

                <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="何をお探しですか？"
                    class="header-search__input">
            </form>
        </div>

        <nav class="header-nav">
            @auth
                <a href="{{ route('items.index') }}" class="header-link">商品一覧</a>
                <a href="{{ route('items.create') }}" class="header-link">出品</a>
                <a href="{{ route('mypage.show') }}" class="header-link">マイページ</a>

                <form method="POST" action="{{ route('logout') }}" class="header-logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="header-link header-link--button">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="header-link">ログイン</a>
                <a href="{{ route('register') }}" class="header-link">会員登録</a>
            @endauth
        </nav>
    </div>
</header>