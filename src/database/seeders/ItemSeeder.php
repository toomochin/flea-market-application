<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

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

        // 1. 仕様書に定義されている正式なカテゴリ一覧を作成
        $categories = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン'
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // 2. 仕様書の商品一覧データと100%一致させたマスターデータ
        $itemsData = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '目立った傷や汚れなし',
                'brand' => 'メンズ', // 仕様書でブランド名欄に指定されている文字に修正
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'categories' => ['家電']
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'condition' => 'やや傷や汚れあり',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'categories' => ['家電']
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'categories' => ['家電']
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'categories' => ['ファッション', 'レディース']
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'categories' => ['キッチン']
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'categories' => ['キッチン']
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'categories' => ['コスメ']
            ],
            [
                'name' => '玉ねぎ',
                'price' => 300,
                'description' => '新鮮な玉ねぎの詰め合わせ',
                'condition' => '目立った傷や汚れなし',
                'brand' => null,
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'categories' => ['キッチン']
            ],
        ];

        // 3. データの登録とカテゴリの完全一致紐付け
        foreach ($itemsData as $data) {
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

            // 仕様書通りのカテゴリIDを取得して正確に同期
            $syncIds = Category::whereIn('name', $data['categories'])->pluck('id')->toArray();
            $item->categories()->sync($syncIds);
        }
    }
}