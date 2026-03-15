@extends('layouts.app')

@section('title', 'ログイン')
@section('body_class', 'auth-body')
@section('main_class', 'auth-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">ログイン</h1>

        {{-- エラー（必要なら） --}}
        @if ($errors->any())
            <div class="form-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input class="form-input" type="password" name="password" required>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-red" type="submit">ログインする</button>

            <div class="auth-links">
                <a class="link" href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </form>
    </div>
@endsection