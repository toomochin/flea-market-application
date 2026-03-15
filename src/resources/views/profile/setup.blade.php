@extends('layouts.app')

@section('title', 'プロフィール設定')
@section('body_class', 'auth-body')
@section('main_class', 'auth-main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@php
    $hasImage = $user->profile_image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_image_path);
@endphp

@section('content')
    <div class="auth-card auth-card--profile">
        <h1 class="auth-title">プロフィール設定</h1>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- アイコン --}}
            <div class="profile-icon">
                <img id="preview" class="icon-circle-img {{ $hasImage ? '' : 'hidden' }}"
                    src="{{ $hasImage ? asset('storage/' . $user->profile_image_path) : '' }}" alt=""
                    onerror="this.classList.add('hidden'); document.getElementById('placeholder').classList.remove('hidden');">

                <div id="placeholder" class="icon-circle {{ $hasImage ? 'hidden' : '' }}"></div>

                <label class="icon-text">
                    画像を選択
                    <input id="profile_image" type="file" name="profile_image" accept="image/*" class="file-hidden">
                </label>

                @error('profile_image')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- 入力 --}}
            <div class="form-group">
                <label class="form-label">ユーザー名</label>
                <input class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}">
                @error('name')<div class="form-error" style="color:red; font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">郵便番号</label>
                <input class="form-input" type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}">
                @error('postcode')<div class="form-error" style="color:red; font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">住所</label>
                <input class="form-input" type="text" name="address" value="{{ old('address', $user->address) }}">
                @error('address')<div class="form-error" style="color:red; font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">建物名</label>
                <input class="form-input" type="text" name="building" value="{{ old('building', $user->building) }}">
                @error('building')<div class="form-error" style="color:red; font-size:12px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-red">送信する</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('profile_image').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        });
    </script>
@endpush