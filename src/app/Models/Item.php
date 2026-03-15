<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'condition',
        'status',
        'image_path',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(\App\Models\Purchase::class);
    }
    /**
     * 画像表示用のパスを自動判別して取得
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            // 代替画像が必要な場合
            return asset('images/no-image.png');
        }

        // httpから始まる（S3や外部URL）ならそのまま返す
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // それ以外（item_images/...）はStorageパスを付与して返す
        return asset('storage/' . $this->image_path);
    }
}