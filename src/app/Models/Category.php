<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // content を name に修正！
    protected $fillable = ['name'];

    // 不要になった contacts の代わりに、items（商品）との紐付けを追加
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}