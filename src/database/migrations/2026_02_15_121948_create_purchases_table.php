<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // ✨ 指摘対応：Laravel標準の外部キー制約を正しく定義
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            $table->string('postcode', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building', 255)->nullable();
            $table->string('payment_method', 50)->default('card');

            $table->timestamps();

            // 1商品は1回だけ購入できるユニーク制約
            $table->unique('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};