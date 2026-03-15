@extends('layouts.app')

@section('title', '送り先住所の変更')
@section('body_class', 'auth-body')
@section('main_class', 'items-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="itemshow-wrap">
        <h1 style="font-size:16px; margin-bottom:12px;">送り先住所の変更</h1>

        @if(session('error'))
            <div style="font-size:12px;color:#c00;margin-bottom:10px;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div style="font-size:12px;color:#0a0;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('purchase.address.update', ['item' => $item->id]) }}"
            style="background:#fff; padding:12px; border-radius:8px;">
            @csrf

            <div style="display:grid; gap:10px;">
                <div>
                    <div style="font-size:12px; color:#555;">郵便番号（必須）</div>
                    <input type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}"
                        style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                    @error('postcode') <div style="font-size:12px;color:#c00;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div style="font-size:12px; color:#555;">住所（必須）</div>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                        style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                    @error('address') <div style="font-size:12px;color:#c00;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div style="font-size:12px; color:#555;">建物名（必須）</div>
                    <input type="text" name="building" value="{{ old('building', $user->building) }}"
                        style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
                    @error('building') <div style="font-size:12px;color:#c00;">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit"
                style="margin-top:14px; width:100%; padding:10px; border:none; border-radius:8px; background:#ff5a5f; color:#fff; font-weight:700;">
                保存する
            </button>

            <div style="margin-top:10px;">
                <a href="{{ session('address_return', route('mypage.show')) }}"
                    style="font-size:12px; color:#333; text-decoration:underline;">
                    戻る
                </a>

            </div>
        </form>
    </div>
@endsection