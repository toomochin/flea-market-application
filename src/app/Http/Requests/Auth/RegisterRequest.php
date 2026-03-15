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
            // 確認用パスワードは confirmed でチェック
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    // ※属性名は使わない（文言固定のため）
    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください',

            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',

            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',

            // 確認用パスワード不一致
            'password.confirmed' => 'パスワードと一致しません',
        ];
    }
}