<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Item $item)
    {
        $user = Auth::user();

        $exists = $user->favorites()
            ->where('item_id', $item->id)
            ->exists();

        if ($exists) {
            $user->favorites()
                ->where('item_id', $item->id)
                ->delete();
        } else {
            $user->favorites()
                ->create([
                    'item_id' => $item->id,
                ]);
        }

        return back();
    }
}
