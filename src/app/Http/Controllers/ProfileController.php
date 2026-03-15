<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ProfileController extends Controller
{
    // PG07: /purchase/address/{item_id}
    public function editAddress(Request $request, Item $item)
    {
        // 戻り先は必ず購入画面
        session(['address_return' => route('purchases.create', $item)]);

        $user = $request->user();
        return view('profile.address', compact('user', 'item'));
    }

    // PG07: POST /purchase/address/{item_id}
    public function updateAddress(Request $request, Item $item)
    {
        $data = $request->validate([
            'postcode' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update($data);

        return redirect(session()->pull('address_return', route('purchases.create', $item)))
            ->with('success', '送り先住所を更新しました。');
    }
}