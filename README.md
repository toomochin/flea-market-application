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

| 分類 | 技術 |
| --- | --- |
| バックエンド | PHP 8.1 / Laravel 8.75 |
| フロントエンド | Blade / Laravel Mix |
| データベース | MySQL 8.0 |
| 認証 | Laravel Fortify |
| 開発環境 | Docker / Docker Compose |
| メール確認 | Mailpit |

## ER図

![ER図](docs/ER図.png)

## セットアップ

### 必要なもの

- Docker Desktop（Docker Compose v2を含む）
- Git

### 1. リポジトリを取得する

```bash
git clone https://github.com/toomochin/flea-market-application.git
cd flea-market-application
```

### 2. コンテナを起動する

```bash
docker compose up -d --build
```

> Docker Compose v1を利用している場合は、`docker compose` を `docker-compose` に読み替えてください。

### 3. Laravelを初期設定する

PHPコンテナに入り、以下を実行します。

```bash
docker compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
```

`.env` のメール設定で、`MAIL_HOST` を `mailpit` に変更してください。

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### 4. テーブルとサンプルデータを作成する

```bash
php artisan migrate:fresh --seed
```

### 5. アプリケーションを開く

| サービス | URL |
| --- | --- |
| アプリケーション | http://localhost |
| phpMyAdmin | http://localhost:8080 |
| Mailpit | http://localhost:8025 |

Mailpitでは、会員登録時に送信されるメール認証用メールを確認できます。

## テスト

PHPコンテナ内で以下を実行します。

```bash
php artisan test
```

## 開発用コマンド

```bash
# コンテナを停止する
docker compose down

# CSS・JavaScriptをビルドする（PHPコンテナ内）
npm install
npm run dev
```

## 注意事項

- `migrate:fresh --seed` はすべてのテーブルを削除して作り直します。既存データがある環境では実行しないでください。
- 商品画像をアップロードして利用する場合は、必要に応じて `php artisan storage:link` を実行してください。
