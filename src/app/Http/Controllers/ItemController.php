<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // ✨ 指摘対応: Blade側のリンクパラメータ（keyword）と完全に統一
        $q = $request->query('keyword');
        $tab = $request->query('tab', 'all');
        $tab = in_array($tab, ['all', 'mylist'], true) ? $tab : 'all';

        // 未ログインで mylist → 何も表示（検索ワード $q も確実に維持してViewへ戻す）
        if ($tab === 'mylist' && !auth()->check()) {
            $items = Item::query()->whereRaw('1=0')->paginate(12)->withQueryString();
            return view('items.index', [
                'items' => $items,
                'keyword' => $q,
                'tab' => $tab
            ]);
        }

        $items = Item::query()
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            // 自分の出品を除外
            ->when(auth()->check(), fn($query) => $query->where('user_id', '!=', auth()->id()))
            ->when($tab === 'mylist', function ($query) {
                $query->whereHas('favorites', fn($q) => $q->where('user_id', auth()->id()));
            })
            ->withCount(['favorites', 'comments'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('items.index', [
            'items' => $items,
            'keyword' => $q,
            'tab' => $tab
        ]);
    }

    public function show(\App\Models\Item $item)
    {
        $item->load([
            'categories',
            'comments.user',
        ])->loadCount([
                    'favorites',
                    'comments',
                ]);

        $isFavorited = Auth::check()
            ? Auth::user()->favorites()->where('item_id', $item->id)->exists()
            : false;

        $isSold = $item->status === 'sold';
        $isMine = Auth::check() && Auth::id() === $item->user_id;

        return view('items.show', compact('item', 'isFavorited', 'isSold', 'isMine'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = $request->user();

        // 画像保存（publicディスク）
        $path = $request->file('image')->store('item_images/' . $user->id, 'public');

        $item = Item::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'status' => 'selling',
            'image_path' => $path,
        ]);

        // 多対多
        $item->categories()->sync($request->categories);

        return redirect()->route('items.show', $item);
    }
}