<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // ID 8, 9: プロフィール情報・住所設定のテスト
    // ==========================================

    public function test_user_can_access_profile_edit_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // ※実際のプロフィール編集画面のURLに合わせて調整してください
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
    }

    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');
        // テスト用のダミー画像を作成
        $file = UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg');

        // ※実際のプロフィール更新のURLに合わせて調整してください
        $response = $this->post('/mypage/profile', [
            // 以下のキー名は、実際のフォーム（Blade）の name 属性に合わせて変更してください
            'name' => '新しい名前',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
            'image' => $file, // profile_image や avatar の場合もあります
        ]);

        // バリデーションエラーがあったらターミナルに表示させる（調査用）
        $response->assertSessionHasNoErrors();

        // 更新後はどこかしらのページにリダイレクトされることを確認
        $response->assertRedirect();

        // データベース（usersテーブル）が正しく更新されたか確認
        // ※もし profiles テーブルなどに分けている場合は 'users' を 'profiles' に変えてください
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '新しい名前',
            'postcode' => '123-4567', // postal_code や zip_code の場合もあります
            'address' => '東京都渋谷区',
            'building' => 'テストビル101',
        ]);
    }
}