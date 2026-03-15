<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createItem($user, $status = 'selling')
    {
        return Item::create([
            'user_id' => $user->id,
            'name' => '購入テスト用商品',
            'price' => 1000,
            'condition' => '良好',
            'description' => 'テスト',
            'status' => $status,
            'image_path' => 'dummy.jpg',
        ]);
    }

    // ==========================================
    // ID 10, 11: 商品購入機能のテスト
    // ==========================================

    public function test_user_can_access_purchase_page()
    {
        $seller = User::factory()->create();

        // ★ 住所未設定で弾かれないように、プロフィール完了＋住所付きのユーザーを作成
        $buyer = User::factory()->create([
            'profile_completed' => true,
            'postcode' => '123-4567',
            'address' => '東京都テスト区',
        ]);

        $item = $this->createItem($seller);

        $this->actingAs($buyer);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('購入テスト用商品');
    }

    public function test_user_can_purchase_an_item()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'profile_completed' => true,
            'postcode' => '123-4567',
            'address' => '東京都テスト区',
        ]);
        $item = $this->createItem($seller);

        $this->actingAs($buyer);

        $response = $this->post("/purchase/{$item->id}", [
            // ★ 日本語ではなく英語のキーに変更（コンビニ払いなら convenience が一般的です）
            'payment_method' => 'convenience',

            'postcode' => '987-6543',
            'address' => '大阪府テスト市',
            'building' => 'テストビル',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        // ※ 'purchases' や 'orders' などテーブル名に合わせて変更
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect();
    }

    public function test_sold_item_cannot_be_purchased_again()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'profile_completed' => true,
            'postcode' => '123-4567',
            'address' => '東京都テスト区',
        ]);

        $item = $this->createItem($seller, 'sold');

        $this->actingAs($buyer);

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'convenience',
        ]);

        $response->assertRedirect();
    }
}