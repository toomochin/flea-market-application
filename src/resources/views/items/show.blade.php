@extends('layouts.app')

@section('title', '商品詳細')
@section('body_class', 'auth-body')
@section('main_class', 'items-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item-show.css') }}">
@endpush

@section('content')
        <div class="itemshow-wrap">

            <div class="itemshow-grid">
                {{-- 左：画像 --}}
                <div class="itemshow-image">
                    @if($item->image_path)
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://'])
            ? $item->image_path
            : asset('storage/' . $item->image_path) }}" alt="">
                    @else
                        商品画像
                    @endif
                </div>

                {{-- 右：情報 --}}
                <div>
                    <h1 class="itemshow-title">{{ $item->name }}</h1>
                    <div class="itemshow-brand">{{ $item->brand ?: 'ブランド名' }}</div>
                    <div class="itemshow-price">¥{{ number_format($item->price) }}（税込）</div>

                    <div class="itemshow-actions">
                        <form method="POST" action="{{ route('items.favorite', $item) }}">
                            @csrf
                            <button class="icon-btn" type="submit">
                                <img class="icon-img"
                                    src="{{ asset($isFavorited ? 'images/icons/heart-on.png' : 'images/icons/heart.png') }}"
                                    alt="いいね">
                                <span>{{ $item->favorites_count }}</span>
                            </button>
                        </form>

                        <div class="icon-btn" style="cursor:default;">
                            <img class="icon-img" src="{{ asset('images/icons/comment.png') }}" alt="コメント">
                            <span>{{ $item->comments_count }}</span>
                        </div>
                    </div>

                    @php
    $isSold = (($item->status ?? '') === 'sold');
    $isMine = auth()->check() && auth()->id() === $item->user_id;
                    @endphp

                    @if($isSold)
                        <div class="itemshow-buy" style="background:#aaa; cursor:not-allowed; text-align:center;">
                            Sold
                        </div>
                    @elseif($isMine)
                        <div class="itemshow-buy" style="background:#aaa; cursor:not-allowed; text-align:center;">
                            出品した商品です
                        </div>
                    @else
                        <a class="itemshow-buy" href="{{ route('purchases.create', $item) }}">
                            購入手続きへ
                        </a>
                    @endif

                    <div class="section-title">商品説明</div>
                    <div class="itemshow-desc">
                        {{ $item->description ?: '説明が未入力です。' }}
                    </div>

                    <div class="section-title">商品の情報</div>

                    <div class="info-row">
                        <div class="info-key">カテゴリー</div>
                        <div class="badges">
                            @forelse($item->categories as $cat)
                                <span class="badge">{{ $cat->name }}</span>
                            @empty
                                <span class="badge">未設定</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-key">商品の状態</div>
                        <div>{{ $item->condition }}</div>
                    </div>
                </div>
            </div>

            {{-- コメント一覧 --}}
            <div class="comment-head">コメント（{{ $item->comments_count }}）</div>

            @forelse($item->comments as $comment)
                <div class="comment">
                    @php
                        $img = trim((string) ($comment->user->profile_image_path ?? ''));
                        $avatarSrc = $img
                            ? (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset('storage/' . ltrim($img, '/')))
                            : asset('images/icons/user.png'); // ←無ければ後述のダミー画像を置く
                    @endphp

                    <div class="comment-avatar">
                        <img src="{{ $avatarSrc }}" alt="ユーザー画像">
                    </div>

                    <div class="comment-body">
                        <div class="comment-user">{{ $comment->user->name }}</div>
                        <div class="comment-text">{{ $comment->body }}</div>
                    </div>
                </div>
            @empty
                <div style="font-size:12px; color:#666; margin-top:10px;">
                    コメントはまだありません
                </div>
            @endforelse

            {{-- コメント投稿 --}}
            <div class="section-title" style="margin-top:18px;">商品へのコメント</div>

            @auth
                <form class="comment-form" method="POST" action="{{ route('items.comments.store', $item) }}">
                    @csrf

                    <textarea name="body">{{ old('body') }}</textarea>

                    @error('body')
                        <div class="form-error">{{ $message }}</div>
                    @enderror

                    <button class="comment-submit" type="submit">
                        コメントを送信する
                    </button>
                </form>
            @else
                <div style="font-size:12px; color:#666; margin-top:10px;">
                    コメントするにはログインが必要です。
                </div>
            @endauth

        </div>
@endsection