@extends('layouts.app')

@section('title', '商品一覧')
@section('body_class', 'auth-body')
@section('main_class', 'items-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endpush

@section('content')
    <div class="items-container">

        <div class="items-tabs">
            <!-- ✨ 検索ワード (keyword) の状態を維持してタブ切り替え -->
            <a href="{{ route('items.index', array_filter(['tab' => 'all', 'keyword' => request('keyword')])) }}"
                class="items-tab {{ ($tab ?? 'all') === 'all' ? 'is-active' : '' }}">
                おすすめ
            </a>

            <!-- ✨ 検索ワード (keyword) の状態を維持してタブ切り替え -->
            <a href="{{ route('items.index', array_filter(['tab' => 'mylist', 'keyword' => request('keyword')])) }}"
                class="items-tab {{ ($tab ?? 'all') === 'mylist' ? 'is-active' : '' }}">
                マイリスト
            </a>
        </div>

        <!-- ✨ 分岐構造を完全にクリーン化 -->
        @if($items->isEmpty())
            <div style="font-size:12px;color:#666;margin-top:10px;">
                {{ ($tab ?? 'all') === 'mylist' ? 'マイリストはまだありません' : '商品がありません' }}
            </div>
        @else
            <div class="items-grid">
                @foreach($items as $item)
                    <a class="item-card" href="{{ route('items.show', $item) }}">
                        <div class="item-thumb" style="position:relative;">
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://'])
                        ? $item->image_path
                        : asset('storage/' . $item->image_path) }}" alt="">

                            @if(($item->status ?? '') === 'sold')
                                <div
                                    style="position:absolute;left:8px;top:8px;background:#000;color:#fff;font-size:12px;padding:4px 8px;border-radius:4px;">
                                    Sold
                                </div>
                            @endif
                        </div>

                        <div class="item-name">{{ $item->name }}</div>
                    </a>
                @endforeach
            </div>

            @if ($items->hasPages())
                <div class="pagination">
                    {{ $items->links() }}
                </div>
            @endif
        @endif

    </div>
@endsection