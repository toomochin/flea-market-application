<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    // 購入確認画面 (PG06)
    public function create(Request $request, Item $item)
    {
        if ($item->status !== 'selling') {
            return redirect()->route('items.show', $item)->with('error', 'この商品は購入できません。');
        }

        $user = $request->user();

        // セッションから変更後の住所を取得（なければプロフィールの住所）
        $address = session("shipping_address_{$item->id}", [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // どちらも未設定なら住所変更画面へ強制誘導
        if (empty($address['postcode']) || empty($address['address'])) {
            return redirect()
                ->route('purchase.address.edit', $item)
                ->with('error', '購入前に配送先住所を登録してください。');
        }

        return view('purchases.create', compact('item', 'user', 'address'));
    }

    // 住所変更画面の表示 (PG07) - ✨新規追加
    public function editAddress(Item $item)
    {
        $user = auth()->user();

        $address = session("shipping_address_{$item->id}", [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchases.address', compact('item', 'address'));
    }

    // 住所変更の保存処理 - ✨新規追加
    public function updateAddress(Request $request, Item $item)
    {
        $request->validate([
            'postcode' => ['required', 'string', 'max:20'], // ハイフン有無などで調整してください
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ]);

        // DBのusersテーブルは書き換えず、セッションに一時保存する（今回限りの配送先）
        session(["shipping_address_{$item->id}" => $request->only(['postcode', 'address', 'building'])]);

        return redirect()->route('purchase.create', $item);
    }

    // 購入確定処理
    public function store(PurchaseRequest $request, Item $item)
    {
        $user = $request->user();

        // 確定時もセッション優先で住所を取得
        $address = session("shipping_address_{$item->id}", [
            'postcode' => $user->postcode,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        if (empty($address['postcode']) || empty($address['address'])) {
            return redirect()->route('purchase.address.edit', $item);
        }

        try {
            DB::transaction(function () use ($request, $item, $user, $address) {
                $locked = Item::where('id', $item->id)->lockForUpdate()->first();

                if (($locked->status ?? '') !== 'selling') {
                    abort(409, 'この商品は購入できません。');
                }

                if (Purchase::where('item_id', $locked->id)->exists()) {
                    abort(409, 'この商品は購入できません。');
                }

                Purchase::create([
                    'user_id' => $user->id,
                    'item_id' => $locked->id,

                    // セッション（またはプロフィール）の住所を記録
                    'postcode' => $address['postcode'],
                    'address' => $address['address'],
                    'building' => $address['building'],

                    'payment_method' => $request->payment_method,
                ]);

                $locked->update([
                    'status' => 'sold',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', '購入処理に失敗しました。もう一度お試しください。');
        }

        // 購入が完了したらセッションの住所データを消去
        session()->forget("shipping_address_{$item->id}");

        return redirect()->route('items.index')->with('success', '購入が完了しました。');
    }
}