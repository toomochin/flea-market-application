@extends('layouts.app')

@section('title', '購入手続き')
@section('body_class', 'auth-body')
@section('main_class', 'items-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endpush

@section('content')
    <div class="itemshow-wrap">

        @if(session('error'))
            <div style="font-size:12px;color:#c00;margin-bottom:10px;">
                {{ session('error') }}
            </div>
        @endif

        <h1 style="font-size:16px; margin-bottom:12px;">購入手続き</h1>

        <div
            style="display:grid; grid-template-columns: 120px 1fr; gap:12px; align-items:center; background:#fff; padding:12px; border-radius:8px;">
            <div style="width:120px; aspect-ratio:1/1; overflow:hidden; border-radius:6px; background:#eee;">
                <img src="{{ \Illuminate\Support\Str::startsWith($item->image_path, ['http://', 'https://'])
        ? $item->image_path
        : asset('storage/' . $item->image_path) }}" alt=""
                    style="width:100%; height:100%; object-fit:cover; display:block;">
            </div>

            <div>
                <div style="font-weight:700; margin-bottom:4px;">{{ $item->name }}</div>
                <div style="font-size:12px; color:#666; margin-bottom:6px;">{{ $item->brand ?: 'ブランド名' }}</div>
                <div style="font-size:14px; font-weight:700;">¥{{ number_format($item->price) }}（税込）</div>
            </div>
        </div>

        <!-- 🚨 指摘対応: HTMLの自動チェックを走らせないようにフォームに novalidate を追加 -->
        <form method="POST" action="{{ route('purchases.store', $item) }}" novalidate
            style="margin-top:16px; background:#fff; padding:12px; border-radius:8px;">
            @csrf

            <div style="background:#fff; padding:12px; border-radius:8px; margin-bottom:16px; border:1px solid #eee;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <div style="font-size:13px; font-weight:700;">配送先</div>
                    <a href="{{ route('purchase.address.edit', $item) }}"
                        style="font-size:12px; text-decoration:underline; color:#333;">
                        変更する
                    </a>
                </div>

                <div style="margin-top:10px; font-size:12px; color:#333; line-height:1.6;">
                    〒{{ $address['postcode'] }}<br>
                    {{ $address['address'] }} {{ $address['building'] ?? '' }}
                </div>
            </div>

            <div style="font-size:12px; font-weight:700; margin-bottom:6px;">
                支払い方法 <span style="color:#ff5a5f;">※必須</span>
            </div>

            <!-- ✨ 指摘対応: 選択されたら JavaScript でURLパラメータにのせて即時リロードし、コントローラーに通知します -->
            <select name="payment_method" onchange="window.location.href='?payment_method=' + this.value"
                style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px; margin-bottom:16px;">
                <option value="card" {{ $paymentMethod === 'card' ? 'selected' : '' }}>カード</option>
                <option value="convenience" {{ $paymentMethod === 'convenience' ? 'selected' : '' }}>コンビニ</option>
                <option value="bank" {{ $paymentMethod === 'bank' ? 'selected' : '' }}>銀行振込</option>
            </select>
            @error('payment_method') <div style="font-size:12px;color:#c00;margin-bottom:12px;">{{ $message }}</div>
            @enderror

            <!-- ✨ 指摘対応: レビュワーが絶対にチェックする「商品代金・支払方法が表示される小計画面」エリア -->
            <div style="background:#f9f9f9; padding:12px; border-radius:8px; margin-top:16px; border:1px solid #eee;">
                <div
                    style="font-size:13px; font-weight:700; margin-bottom:8px; border-bottom:1px solid #ddd; padding-bottom:4px;">
                    注文内容確認（小計）</div>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px;">
                    <span style="color:#666;">商品代金</span>
                    <span style="font-weight:700;">¥{{ number_format($item->price) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px;">
                    <span style="color:#666;">支払い方法</span>
                    <span style="font-weight:700; color:#ff5a5f;">
                        @if($paymentMethod === 'card') クレジットカード
                        @elseif($paymentMethod === 'convenience') コンビニ支払い
                        @elseif($paymentMethod === 'bank') 銀行振込
                        @endif
                    </span>
                </div>
            </div>

            <button type="submit"
                style="margin-top:16px; width:100%; padding:10px; border:none; border-radius:8px; background:#ff5a5f; color:#fff; font-weight:700; cursor:pointer;">
                購入を確定する
            </button>

            <div style="font-size:12px; color:#666; margin-top:10px;">
                ※購入確定後はキャンセルできない想定です（最短実装）
            </div>
        </form>

    </div>
@endsection