@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
    <div class="confirm__content">
        <div class="confirm__heading">
            <h2>お問い合わせ内容確認</h2>
        </div>
@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
        <form class="form" action="/contacts" method="post">
            @csrf
            <input type="hidden" name="first_name" value="{{ $contact['first_name'] }}">
            <input type="hidden" name="last_name" value="{{ $contact['last_name'] }}">
            <input type="hidden" name="gender" value="{{ $contact['gender'] }}">
            <input type="hidden" name="email" value="{{ $contact['email'] }}">
            <input type="hidden" name="tel" value="{{ $contact['tel'] }}">
            <input type="hidden" name="address" value="{{ $contact['address'] }}">
            <input type="hidden" name="building" value="{{ $contact['building'] ?? '' }}">
            <input type="hidden" name="category_id" value="{{ $contact['category_id'] }}">
            <textarea name="detail" hidden>{{ $contact['detail'] }}</textarea>
            <div class="confirm-table">
                <table class="confirm-table__inner">

                    {{-- お名前 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お名前</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['first_name'] }}  {{ $contact['last_name'] }}" readonly />
                        </td>
                    </tr>

                    {{-- 性別（表示用） --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">性別</th>
                        <td class="confirm-table__text">
                            <input type="text"
                                value="@if($contact['gender']==1)男性@elseif($contact['gender']==2)女性@elseその他@endif"
                                readonly />
                        </td>
                    </tr>

                    {{-- メールアドレス --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">メールアドレス</th>
                        <td class="confirm-table__text">
                            <input type="email" value="{{ $contact['email'] }}" readonly />
                        </td>
                    </tr>

                    {{-- 電話番号 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">電話番号</th>
                        <td class="confirm-table__text">
                            <input type="tel" value="{{ $contact['tel'] }}" readonly />
                        </td>
                    </tr>

                    {{-- 住所 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">住所</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['address'] }}" readonly />
                        </td>
                    </tr>

                    {{-- 建物名 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">建物名</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['building'] ?? '' }}" readonly />
                        </td>
                    </tr>

                    {{-- お問い合わせの種類 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お問い合わせの種類</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['category_name'] }}" readonly />
                        </td>
                    </tr>

                    {{-- お問い合わせ内容 --}}
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お問い合わせ内容</th>
                        <td class="confirm-table__text">
                            <input type="text" name="content" value="{{ $contact['detail'] }}" readonly />
                        </td>
                    </tr>

                </table>
            </div>

            <div class="form__button">
                <button class="form__button-submit" type="submit">送信</button>
                <button class="form__button-back" type="button" onclick="history.back()">修正</button>
            </div>
        </form>
    </div>
@endsection