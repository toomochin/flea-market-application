<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    // 項目名を日本語にする（email / password を置き換え）
    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }

    // メッセージをログイン画面用に自然な日本語にする
    public function messages(): array
    {
        return [
            'email.required' => ':attributeを入力してください。',
            'email.email' => ':attributeの形式が正しくありません。',
            'password.required' => ':attributeを入力してください。',
        ];
    }
}
