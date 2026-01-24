@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    <div class="contact-form__content">
        <div class="contact-form__heading">
            <h2>Contact</h2>
        </div>

        <form class="form" action="/contacts/confirm" method="post">
            @csrf

            {{-- お名前 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お名前</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--name">
                        <input type="text" name="first_name" placeholder="例: 山田" value="{{ old('first_name') }}">
                        <input type="text" name="last_name" placeholder="例: 太郎" value="{{ old('last_name') }}">
                    </div>
                    <div class="form__error">
                        @error('first_name')
                            <p class="form__error-message">{{ $message }}</p>
                        @enderror
                        @error('last_name')
                            <p class="form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 性別 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">性別</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="form__radio--gender">
                        <label>
                            <input type="radio" name="gender" value="1" {{ old('gender') == 1 ? 'checked' : '' }}>
                            男性
                        </label>
                        <label>
                            <input type="radio" name="gender" value="2" {{ old('gender') == 2 ? 'checked' : '' }}>
                            女性
                        </label>
                        <label>
                            <input type="radio" name="gender" value="3" {{ old('gender') == 3 ? 'checked' : '' }}>
                            その他
                        </label>
                        <div class="form__error">
                            @error('gender')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- メール --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">メールアドレス</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="email" name="email" placeholder="test@example.com" value="{{ old('email') }}" />
                    </div>
                    <div class="form__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 電話番号 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">電話番号</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--tel">
                        <input type="text" name="tel_1" placeholder="080" value="{{ old('tel_1') }}">
                        <span>-</span>
                        <input type="text" name="tel_2" placeholder="1234" value="{{ old('tel_2') }}">
                        <span>-</span>
                        <input type="text" name="tel_3" placeholder="5678" value="{{ old('tel_3') }}">
                    </div>
                    <div class="form__error">
                        @error('tel_1')
                            <p class="form__error-message">{{ $message }}</p>
                        @enderror
                        @error('tel_2')
                            <p class="form__error-message">{{ $message }}</p>
                        @enderror
                        @error('tel_3')
                            <p class="form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 住所 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">住所</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <input type="text" name="address" placeholder="東京" value="{{ old('address') }}">
                    <div class="form__error">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 建物名 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">建物名</span>
                </div>
                <div class="form__group-content">
                    <input type="text" name="building" placeholder="●●ハイツ" value="{{ old('building') }}">
                </div>
            </div>

            {{-- お問い合わせの種類 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お問い合わせの種類</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <select name="category_id">
                        <option value="">選択してください</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->content }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form__error">
                        @error('category_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            {{-- お問い合わせ内容 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">お問い合わせ内容</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--textarea">
                        <textarea name="detail" placeholder="資料をいただきたいです">{{ old('detail') }}</textarea>
                    </div>
                    <div class="form__error">
                        @error('detail')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form__button">
                <button class="form__button-submit" type="submit">確認画面へ</button>
            </div>
        </form>
    </div>

@endsection