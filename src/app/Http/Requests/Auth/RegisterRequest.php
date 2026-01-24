<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    // フィールド名を日本語に
    public function attributes(): array
    {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }

    // 登録画面専用の自然な日本語メッセージ
    public function messages(): array
    {
        return [
            'name.required' => ':attributeを入力してください。',
            'name.max' => ':attributeは50文字以内で入力してください。',

            'email.required' => ':attributeを入力してください。',
            'email.email' => ':attributeの形式が正しくありません。',
            'email.unique' => 'この:attributeはすでに登録されています。',

            'password.required' => ':attributeを入力してください。',
            'password.min' => ':attributeは8文字以上で入力してください。',
        ];
    }
}
