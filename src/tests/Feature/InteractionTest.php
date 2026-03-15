<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InteractionTest extends TestCase
{
    use RefreshDatabase;

    // ダミー商品を作成する補助メソッド
    private function createItem($user)
    {
        return Item::create([
            'user_id' => $user->id,
            'name' => 'インタラクションテスト商品',
            'price' => 1000,
            'condition' => '良好',
            'description' => 'テスト',
            'status' => 'selling',
            'image_path' => 'dummy.jpg',
        ]);
    }

    // ==========================================
    // ID 12: いいね機能のテスト
    // ==========================================
    public function test_user_can_like_and_unlike_an_item()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = $this->createItem($seller);

        $this->actingAs($buyer);

        // 1. いいねを追加する
        $response = $this->post("/item/{$item->id}/favorite");
        $response->assertStatus(302); // 実行後は元の画面などにリダイレクトされる想定

        // DBに「いいね（favorites）」が保存されたか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        // 2. もう一度同じURLにPOSTして、いいねを解除する（トグル機能）
        $response = $this->post("/item/{$item->id}/favorite");

        // DBから「いいね（favorites）」が消えたか確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    // ==========================================
    // ID 13, 14: コメント機能のテスト
    // ==========================================
    public function test_user_can_add_a_comment()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = $this->createItem($seller);

        $this->actingAs($buyer);

        // コメントを送信する
        $response = $this->post("/item/{$item->id}/comments", [
            'body' => '購入を検討しています！', // show.blade.php の name="body" に対応
        ]);

        $response->assertStatus(302); // 実行後はリダイレクトされる想定
        $response->assertSessionHasNoErrors(); // バリデーションエラーがないこと

        // DBにコメントが保存されたか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'body' => '購入を検討しています！',
        ]);
    }

    public function test_guest_cannot_add_a_comment()
    {
        $seller = User::factory()->create();
        $item = $this->createItem($seller);

        // ログインせずにコメントを送信してみる
        $response = $this->post("/item/{$item->id}/comments", [
            'body' => 'ゲストからの悪戯コメント',
        ]);

        // ログイン画面に弾き返される想定
        $response->assertRedirect('/login');

        // DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'body' => 'ゲストからの悪戯コメント',
        ]);
    }
}