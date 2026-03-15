<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Arr;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // ユーザー取得（なければ作成）
        $user = User::first() ?? User::factory()->create([
            'name' => 'ダミーユーザー',
            'email' => 'dummy@example.com',
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        // カテゴリ（content）
        $categoryMap = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
        ];

        foreach ($categoryMap as $c) {
            Category::firstOrCreate(['name' => $c]);
        }

        $categoryIds = Category::pluck('id')->toArray();

        // 商品データ（S3画像URL）
        $items = [
            [
                'name' => '腕時計',
                'brand' => 'EMPORIO ARMANI',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計。',
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            ],
            [
                'name' => 'HDD',
                'brand' => null,
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク。',
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            ],
            [
                'name' => '革靴',
                'brand' => null,
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴。',
                'condition' => 'やや傷や汚れあり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'name' => 'ノートPC',
                'brand' => null,
                'price' => 45000,
                'description' => '高性能なノートパソコン。',
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            ],
            [
                'name' => 'マイク',
                'brand' => null,
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク。',
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ。',
                'condition' => 'やや傷や汚れあり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            ],
            [
                'name' => 'タンブラー',
                'brand' => null,
                'price' => 500,
                'description' => '使いやすいタンブラー。',
                'condition' => '状態が悪い',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => null,
                'price' => 4000,
                'description' => '手動のコーヒーミル。',
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'name' => 'メイクセット',
                'brand' => null,
                'price' => 2500,
                'description' => '便利なメイクアップセット。',
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            ],
        ];

        foreach ($items as $data) {
            $item = Item::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'brand' => $data['brand'],
                'price' => $data['price'],
                'description' => $data['description'],
                'condition' => $data['condition'],
                'status' => 'selling',
                'image_path' => $data['image_path'],
            ]);

            // カテゴリを1〜2個ランダム付与
            $item->categories()->sync(
                Arr::random($categoryIds, rand(1, 2))
            );
        }
    }
}
