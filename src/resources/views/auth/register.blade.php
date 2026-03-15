@extends('layouts.app')

@section('title', '会員登録')
@section('body_class', 'auth-body')
@section('main_class', 'auth-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-card">
        <h1 class="auth-title">会員登録</h1>

        @if ($errors->any())
            <div class="form-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">お名前</label>
                <input class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">メールアドレス</label>
                <input class="form-input" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">パスワード</label>
                <input class="form-input" type="password" name="password" required>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">確認用パスワード</label>
                <input class="form-input" type="password" name="password_confirmation" required>
            </div>

            <button class="btn btn-red" type="submit">登録</button>

            <div class="auth-links">
                <a class="link" href="{{ route('login') }}">ログインの方はこちら</a>
            </div>
        </form>
    </div>
@endsection