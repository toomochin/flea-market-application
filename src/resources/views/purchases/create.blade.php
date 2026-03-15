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

        <form method="POST" action="{{ route('purchases.store', $item) }}"
            style="margin-top:16px; background:#fff; padding:12px; border-radius:8px;">
            @csrf

            <div style="background:#fff; padding:12px; border-radius:8px; margin-top:16px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <div style="font-size:13px; font-weight:700;">配送先</div>
                    <a href="{{ route('purchase.address.edit', $item) }}"
                        style="font-size:12px; text-decoration:underline; color:#333;">
                        変更する
                    </a>
                </div>

                <div style="margin-top:10px; font-size:12px; color:#333; line-height:1.6;">
                    〒{{ $address['postcode'] }}
                    {{ $address['address'] }} {{ $address['building'] ?? '' }}
                </div>
            </div>


            <div style="font-size:12px; font-weight:700;">
                支払い方法 <span style="color:#ff5a5f;">※必須</span>
            </div>

            <select name="payment_method" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                <option value="card" {{ old('payment_method', 'card') === 'card' ? 'selected' : '' }}>カード</option>
                <option value="convenience" {{ old('payment_method') === 'convenience' ? 'selected' : '' }}>コンビニ</option>
                <option value="bank" {{ old('payment_method') === 'bank' ? 'selected' : '' }}>銀行振込</option>
            </select>
            @error('payment_method') <div style="font-size:12px;color:#c00;">{{ $message }}</div> @enderror

            <button type="submit"
                style="margin-top:14px; width:100%; padding:10px; border:none; border-radius:8px; background:#ff5a5f; color:#fff; font-weight:700;">
                購入を確定する
            </button>

            <div style="font-size:12px; color:#666; margin-top:10px;">
                ※購入確定後はキャンセルできない想定です（最短実装）
            </div>
        </form>

    </div>
@endsection