<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        // ログイン必須のルートで使用するため true でOK
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:2000'],
            'condition' => ['required', 'string', 'max:50'],
            'image' => ['required', 'image', 'max:4096'],
            'categories' => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0以上で入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.string' => '商品の説明は文字列で入力してください',
            'description.max' => '商品の説明は2000文字以内で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '画像ファイルを指定してください',
            'image.max' => '画像サイズは4MB以下にしてください',
            'categories.required' => 'カテゴリーを選択してください',
            'categories.array' => 'カテゴリーの形式が正しくありません',
            'categories.*.exists' => '選択されたカテゴリーは無効です',
        ];
    }
}