<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MypageController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $page = $request->query('page', 'sell'); // sell | buy

        if ($page === 'buy') {
            // 購入した商品一覧
            $items = Item::whereHas('purchases', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->withCount(['favorites', 'comments'])
                ->latest()
                ->paginate(12)
                ->withQueryString();
        } else {
            // 出品した商品一覧
            $items = Item::where('user_id', $user->id)
                ->withCount(['favorites', 'comments'])
                ->latest()
                ->paginate(12)
                ->withQueryString();
        }

        return view('mypage.show', compact('user', 'items', 'page'));
    }
}