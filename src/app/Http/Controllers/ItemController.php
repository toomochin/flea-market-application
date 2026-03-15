<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest; // 上部に追記

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // header.blade.php の input name="keyword" に合わせる
        $q = $request->query('keyword');
        $tab = $request->query('tab', 'all');
        $tab = in_array($tab, ['all', 'mylist'], true) ? $tab : 'all';

        // 未ログインで mylist → 何も表示
        if ($tab === 'mylist' && !auth()->check()) {
            $items = Item::query()->whereRaw('1=0')->paginate(12)->withQueryString();
            return view('items.index', compact('items', 'q', 'tab'));
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

        // compact内もキーワード検索に使う変数名（keyword）で渡すと確実
        return view('items.index', [
            'items' => $items,
            'keyword' => $q, // $q を keyword という名前でViewに渡す
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
            'brand' => $request->brand, // 任意項目なのでそのまま
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'status' => 'selling', // ✅ 固定
            'image_path' => $path,
        ]);

        // 多対多
        $item->categories()->sync($request->categories);

        return redirect()->route('items.show', $item);
    }
}
