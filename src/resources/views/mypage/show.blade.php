@extends('layouts.app')

@section('title', 'マイページ')
@section('body_class', 'auth-body')
@section('main_class', 'items-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')

    <div class="mypage">

        {{-- ヘッダー（プロフィールエリア） --}}
        <div class="mypage__header">
            <div class="mypage__avatar">
                @php
                    $img = trim((string) ($user->profile_image_path ?? ''));
                    $src = $img
                        ? (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                            ? $img
                            : asset('storage/' . ltrim($img, '/')))
                        : asset('images/icons/user.png');
                @endphp
                <img src="{{ $src }}" alt="{{ $user->name }}">
            </div>

            <div class="mypage__center">
                <div class="mypage__name">{{ $user->name }}</div>
            </div>

            <div class="mypage__actions">
                <a href="{{ route('profile.edit') }}" class="mypage__editBtn">
                    プロフィールを編集
                </a>
            </div>
        </div>

        {{-- タブ --}}
        <div class="mypage__tabs">
            <a href="{{ route('mypage.show', ['page' => 'sell']) }}" 
               class="mypage__tab {{ $page === 'sell' ? 'is-active' : '' }}">
                出品した商品
            </a>

            <a href="{{ route('mypage.show', ['page' => 'buy']) }}" 
               class="mypage__tab {{ $page === 'buy' ? 'is-active' : '' }}">
                購入した商品
            </a>
        </div>

        {{-- 商品一覧 --}}
        @if($items->isEmpty())
            <div class="mypage__empty" style="font-size:14px; color:#666;">
                {{ $page === 'buy' ? '購入した商品はありません' : '出品した商品はありません' }}
            </div>
        @else
            <div class="item-grid">
                @foreach($items as $item)
                    <a class="item-card" href="{{ route('items.show', $item) }}">
                        <div class="item-card__img" style="position:relative;">
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://'])
                                ? $item->image_path
                                : asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">

                            @if(($item->status ?? '') === 'sold')
                                <div style="position:absolute; top:0; left:0; background:#e50012; color:#fff; font-size:12px; font-weight:bold; padding:4px 12px; letter-spacing:1px;">
                                    Sold
                                </div>
                            @endif
                        </div>

                        <div class="item-card__body">
                            <div class="item-card__name">{{ $item->name }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                {{ $items->links() }}
            </div>
        @endif

    </div>

@endsection