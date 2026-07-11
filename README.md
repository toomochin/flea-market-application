# フリマアプリ

Laravelで作成したフリーマーケットアプリケーションです。ユーザー登録から商品出品、商品検索、お気に入り、コメント、購入までを利用できます。

## 主な機能

- ユーザー登録・ログイン・メール認証
- プロフィール・配送先住所の登録と編集
- 商品一覧・キーワード検索・マイリスト表示
- 商品詳細の表示、コメント投稿、お気に入り登録
- 商品の出品と画像アップロード
- 購入手続き（支払い方法・配送先の指定）
- マイページで出品商品・購入商品を確認
- お問い合わせ管理

## 使用技術

- PHP 8.x / Laravel 8
- MySQL 8.0
- Nginx 1.21
- Laravel Fortify
- Docker Compose
- Mailpit（開発用メール受信）

## 環境構築

Docker ビルド

1. git clone git@github.com:toomochin/flea-market-application.git
2. docker-compose up -d --build

Lavaral 環境構築

1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .env ファイルの変更

```
　DB_HOSTをmysqlに変更
　DB_DATABASEをlaravel_dbに変更
　DB_USERNAMEをlaravel_userに変更
　DB_PASSWORDをlaravel_passに変更
　MAIL_FROM_ADDRESSに送信元アドレスを設定
```

5. php artisan key:generate
6. php artisan migrate
7. php artisan db:seed
8. php artisan test

## メール認証

開発環境ではメール確認用に Mailpit を使用しています。`.env` を次のように設定してください。

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS=admin@example.com
```

会員登録後に送信される認証メールは、[Mailpit](http://localhost:8025) で確認できます。

## データベース概要

| テーブル | 用途 |
| |
| `users` | ユーザー情報およびプロフィール情報 |
| `items` | 出品された商品情報（価格、説明、画像パス等） |
| `categories` | 商品のカテゴリ情報 |
| `category_item` | 商品とカテゴリを結ぶ中間テーブル（多対多） |
| `favorites` | お気に入り（いいね）情報（user_id と item_id のユニーク制約） |
| `comments` | 商品に対するユーザーのコメント履歴 |
| `purchases` | 商品の購入履歴（1商品につき1購入の制限付き） |

## ER図

![ER図](docs/ER図.png)

## 初期アカウント

`make init` または `make fresh` 実行時に、以下のアカウントが作成されます。パスワードはすべて `password` です。

| 役割 / アカウント名 | メールアドレス     | パスワード | 初期状態 |
| ------------------- | ------------------ | ---------- | -------- |
| 出品者 / テスト太郎 | dummy1@example.com | password   |
| 購入者 / テスト花子 | dummy2@example.com | password   |

## アプリケーションを開く

| サービス         | URL                   |
| ---------------- | --------------------- |
| アプリケーション | http://localhost      |
| phpMyAdmin       | http://localhost:8080 |
| Mailpit          | http://localhost:8025 |

Mailpitでは、会員登録時に送信されるメール認証用メールを確認できます。

## テストの実行

テスト用データベースを作成してから、PHP コンテナ内でテストを実行します。

```bash
# テスト用データベースの作成（初回のみ）
docker-compose exec mysql mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS test_database;"
# パスワードは root を入力

# テストの実行
docker-compose exec php php artisan test
```

## ディレクトリ構成

```text
.
├── docker/              # PHP・Nginx・MySQL の Docker 設定
├── src/                 # Laravel アプリケーション
│   ├── app/             # コントローラー、モデルなど
│   ├── database/        # マイグレーション、シーダー
│   ├── resources/views/ # Blade テンプレート
│   └── tests/           # Feature / Unit テスト
├── docker-compose.yml
└── Makefile
```
