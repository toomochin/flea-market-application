<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    // ダミー商品を作成する安全な補助メソッド
    private function createItem($user, $status = 'selling', $name = 'テスト商品')
    {
        return Item::create([
            'user_id' => $user->id,
            'name' => $name,
            'price' => 1000,
            'condition' => '良好',
            'description' => 'テスト用の商品説明です。',
            'status' => $status,
            'image_path' => 'dummy.jpg',
        ]);
    }

    // ==========================================
    // ID 4: 商品一覧取得のテスト (済)
    // ==========================================
    public function test_can_get_all_items()
    {
        $user = User::factory()->create();
        $this->createItem($user, 'selling', '出品中の商品一覧テスト');
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('出品中の商品一覧テスト');
    }

    public function test_sold_items_display_sold_label()
    {
        $user = User::factory()->create();
        $this->createItem($user, 'sold', '売り切れ商品');
        $response = $this->get('/');
        $response->assertSee('Sold', false);
    }

    public function test_my_own_items_are_not_displayed()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->createItem($me, 'selling', '私の商品');
        $this->createItem($otherUser, 'selling', '他の人の商品');

        $this->actingAs($me);
        $response = $this->get('/');
        $response->assertDontSee('私の商品');
        $response->assertSee('他の人の商品');
    }

    // ==========================================
    // ID 5: マイリスト一覧取得のテスト (済)
    // ==========================================
    public function test_only_liked_items_are_displayed_in_mylist()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $likedItem = $this->createItem($otherUser, 'selling', 'いいねした商品');
        $notLikedItem = $this->createItem($otherUser, 'selling', 'いいねしてない商品');

        Favorite::create(['user_id' => $me->id, 'item_id' => $likedItem->id]);

        $this->actingAs($me);
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしてない商品');
    }

    public function test_sold_items_display_sold_label_in_mylist()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $soldLikedItem = $this->createItem($otherUser, 'sold', '売り切れのいいね商品');

        Favorite::create(['user_id' => $me->id, 'item_id' => $soldLikedItem->id]);

        $this->actingAs($me);
        $response = $this->get('/?tab=mylist');
        $response->assertSee('Sold', false);
    }

    public function test_unauthenticated_user_cannot_see_mylist()
    {
        $otherUser = User::factory()->create();
        $item = $this->createItem($otherUser, 'selling', 'いいねした商品');
        Favorite::create(['user_id' => $otherUser->id, 'item_id' => $item->id]);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSee('いいねした商品');
    }

    // ==========================================
    // ID 6: 商品検索機能のテスト
    // ==========================================
    public function test_can_search_items_by_name()
    {
        $user = User::factory()->create();
        $this->createItem($user, 'selling', '特別なスニーカー');
        $this->createItem($user, 'selling', '普通のシャツ');

        // キーワード検索
        $response = $this->get('/?keyword=スニーカー'); // 検索のURLパラメータ名に合わせて調整

        $response->assertStatus(200);
        $response->assertSee('特別なスニーカー');
        $response->assertDontSee('普通のシャツ');
    }

    public function test_search_query_is_retained_in_mylist()
    {
        $me = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = $this->createItem($otherUser, 'selling', '特別なスニーカー');

        Favorite::create(['user_id' => $me->id, 'item_id' => $item->id]);

        $this->actingAs($me);
        // マイリストタブ ＋ 検索キーワード
        $response = $this->get('/?tab=mylist&keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSee('特別なスニーカー');
    }
    // ==========================================
    // ID 7: 商品詳細情報取得のテスト
    // ==========================================
    public function test_item_detail_displays_all_required_information()
    {
        $user = User::factory()->create(['name' => '出品者A']);
        $item = Item::create([
            'user_id' => $user->id,
            'name' => 'テスト商品詳細',
            'brand' => 'テストブランド',
            'price' => 5000,
            'condition' => '新品、未使用',
            'description' => '詳細なテスト説明文です。',
            'status' => 'selling',
            'image_path' => 'dummy.jpg',
        ]);

        $category1 = Category::create(['name' => 'ファッション']);
        $category2 = Category::create(['name' => 'メンズ']);
        $item->categories()->attach([$category1->id, $category2->id]);

        $commentUser = User::factory()->create(['name' => 'コメント投稿者']);
        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'body' => 'これはテストコメントです。'
        ]);
        Favorite::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        // ★ログイン必須な仕様かもしれないので、ログイン状態にしてみる
        $this->actingAs($user);

        $response = $this->get("/item/{$item->id}");

        // ★もし弾かれたら、リダイレクト先のURLをターミナルに表示！
        if ($response->status() === 302) {
            dump('【詳細ページ】リダイレクト先: ' . $response->headers->get('Location'));
        }

        $response->assertStatus(200);
        $response->assertSee('テスト商品詳細');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('新品、未使用');
        $response->assertSee('詳細なテスト説明文です。');
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee('コメント投稿者');
        $response->assertSee('これはテストコメントです。');
    }

    // ==========================================
    // ID 15: 出品商品情報保存のテスト
    // ==========================================
    public function test_user_can_create_an_item()
    {
        $user = User::factory()->create(['profile_completed' => true]);
        $this->actingAs($user);

        $category = Category::create(['name' => '家電']);

        Storage::fake('public');
        $file = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');

        $response = $this->post('/sell', [
            'name' => '新規出品商品',
            'description' => '出品のテストです',
            'price' => 3000,
            'condition' => '目立った傷や汚れなし',
            'categories' => [$category->id], // 👈 コントローラーに合わせて修正！
            'image' => $file,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'name' => '新規出品商品',
            'price' => 3000,
        ]);

        $item = Item::where('name', '新規出品商品')->first();
        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        // 👈 コントローラーの仕様（詳細ページへの遷移）に合わせて修正！
        // ※もしエラーになった場合は route('items.show', $item) 等に変更してください
        $response->assertRedirect("/item/{$item->id}");
    }
}