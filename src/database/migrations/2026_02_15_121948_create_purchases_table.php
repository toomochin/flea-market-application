<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // 外部キーの定義を明示的に記述し、不備を解消する
            $table->foreignId('user_id')->customHasColumns()->nullable(false)->constrained('users')->cascadeOnDelete();
            $table->foreignId('item_id')->customHasColumns()->nullable(false)->constrained('items')->cascadeOnDelete();

            // ※仕様書にない場合は以下の住所3ラインは削除してください
            $table->string('postcode', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building', 255)->nullable();

            // 支払い方法（仕様書で「payment_method_id」などの数値指定があれば、それに合わせる）
            $table->string('payment_method', 50)->default('card');

            $table->timestamps();

            // 1商品は1回だけ購入できる（ユニーク制約）
            $table->unique('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};