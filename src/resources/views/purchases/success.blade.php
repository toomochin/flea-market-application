@extends('layouts.app')

@section('title', '購入完了')

@section('content')
    <div
        style="max-width: 600px; margin: 50px auto; text-align: center; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #28a745; margin-bottom: 20px;">🎉 ご購入ありがとうございました！</h2>
        <p><strong>{{ $item->name }}</strong> の購入手続きが完了しました。</p>
        <p style="margin-top: 10px; color: #666;">商品の発送までしばらくお待ちください。</p>

        <a href="{{ route('items.index') }}"
            style="display: inline-block; margin-top: 30px; padding: 10px 20px; background: #333; color: #fff; text-decoration: none; border-radius: 4px;">
            トップページへ戻る
        </a>
    </div>
@endsection