<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            // 住所（今回は最短で購入時に入力させる）
            $table->string('postcode', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('building', 255)->nullable();

            // 最短：支払い方法は文字列で持つ
            $table->string('payment_method', 50)->default('card');

            $table->timestamps();

            // 1商品は1回だけ購入できる（売り切れ防止）
            $table->unique('item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
