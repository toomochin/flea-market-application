@extends('layouts.app')

@section('title', '商品出品')
@section('body_class', 'auth-body')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/item-create.css') }}">
@endpush

@section('content')
    <div class="sell">
        <h1 class="sell__title">商品の出品</h1>

        <form class="sell__form" method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- 画像 --}}
            <div class="sell__block">
                <h2 class="sell__label">商品画像</h2>

                <div class="sell__imageBox">
                    <img id="preview" class="sell__preview hidden" alt="">
                    <label class="sell__imageBtn">
                        画像を選択する
                        <input type="file" name="image" id="image" accept="image/*" class="sell__fileHidden">
                    </label>
                </div>
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- 詳細 --}}
            <div class="sell__block">
                <h2 class="sell__label">商品の詳細</h2>

                <div class="sell__subLabel">カテゴリー</div>
                <div class="sell__chips">
                    @foreach($categories as $category)
                        <label class="sell__chip">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <span>{{ $category->content }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories')<div class="form-error">{{ $message }}</div>@enderror

                <div class="sell__subLabel">商品の状態</div>
                <select class="sell__select" name="condition">
                    <option value="">選択してください</option>
                    @foreach(['新品', '未使用に近い', '目立った傷や汚れなし', 'やや傷や汚れあり', '傷や汚れあり', '全体的に状態が悪い'] as $c)
                        <option value="{{ $c }}" {{ old('condition') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                @error('condition')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- 商品名と説明 --}}
            <div class="sell__block">
                <h2 class="sell__label">商品名と説明</h2>

                <label class="sell__field">
                    <span>商品名</span>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </label>

                <label class="sell__field">
                    <span>ブランド名</span>
                    <input type="text" name="brand" value="{{ old('brand') }}">
                    @error('brand')<div class="form-error">{{ $message }}</div>@enderror
                </label>

                <label class="sell__field">
                    <span>商品の説明</span>
                    <textarea name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </label>

                <label class="sell__field">
                    <span>販売価格</span>
                    <div class="sell__price">
                        <span>¥</span>
                        <input type="number" name="price" value="{{ old('price') }}" min="0">
                    </div>
                    @error('price')<div class="form-error">{{ $message }}</div>@enderror
                </label>
            </div>

            <button class="sell__submit" type="submit">出品する</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('image')?.addEventListener('change', e => {
            const file = e.target.files?.[0];
            if (!file) return;
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
        });
    </script>
@endpush