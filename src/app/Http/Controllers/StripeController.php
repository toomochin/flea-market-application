<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    // 引数を (Item $item) にすることで、findOrFail の処理が自動化されます
    public function checkout(Item $item)
    {
        if ($item->status === 'sold') {
            return redirect()->back()->with('error', 'この商品はすでに売り切れです。');
        }

        // ✨ 修正ポイント：env() から config() を経由して安全に取得する形に変更
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                            'description' => $item->description ?? '',
                        ],
                        'unit_amount' => $item->price,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['item' => $item->id]),
            'cancel_url' => route('stripe.cancel', ['item' => $item->id]),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, Item $item)
    {
        if ($item->status !== 'sold') {
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => 'card',
                // 必要に応じてセッションやリクエストから住所情報を引き継ぎ可能です
            ]);

            $item->update(['status' => 'sold']);
        }

        return view('purchases.success', compact('item'));
    }

    public function cancel(Item $item)
    {
        return redirect()->route('items.show', $item->id)->with('error', '決済がキャンセルされました。');
    }
}