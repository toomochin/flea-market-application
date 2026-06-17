FROM php:8.1-apache

# 必要なPHP拡張モジュールのインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip

# Apacheの設定（ドキュメントルートをLaravelのpublicに変更）
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# mod_rewriteの有効化（Laravelのルーティング用）
RUN a2enmod rewrite

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ソースコードのコピー
WORKDIR /var/www/html
COPY src/ .

# 権限の設定
# 権限の設定（存在しない場合はフォルダを作成してから権限変更）
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 依存関係のインストール
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

EXPOSE 80