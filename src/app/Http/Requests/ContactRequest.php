<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $base = [
            'category_id' => ['required', 'exists:categories,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:1,2,3'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'string', 'max:500'],
        ];

        // confirm に送るとき（入力画面 → 確認）
        if ($this->is('contacts/confirm') || $this->routeIs('contacts.confirm')) {
            return $base + [
                'tel_1' => ['required', 'digits_between:2,4'],
                'tel_2' => ['required', 'digits_between:2,4'],
                'tel_3' => ['required', 'digits_between:3,4'],
            ];
        }

        // store に送るとき（確認画面 → 保存）
        // POST /contacts
        return $base + [
            'tel' => ['required', 'regex:/^\d{2,4}-\d{2,4}-\d{3,4}$/'],
        ];
    }
    public function messages()
    {
        return [
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.exists' => 'お問い合わせの種類を正しく選択してください',

            'first_name.required' => '姓を入力してください',
            'last_name.required' => '名を入力してください',

            'gender.required' => '性別を選択してください',

            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式が正しくありません',

            'tel_1.required' => '電話番号（1つ目）は2〜4桁で入力してください',
            'tel_2.required' => '電話番号（2つ目）は2〜4桁で入力してください',
            'tel_3.required' => '電話番号（3つ目）は3〜4桁で入力してください',
            'tel' => ['required', 'string', 'regex:/^\d{2,4}-\d{2,4}-\d{3,4}$/'],

            'address.required' => '住所を入力してください',

            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}
