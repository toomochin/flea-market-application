<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites')->withTimestamps();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavorited(Item $item): bool
    {
        return $this->favorites()->where('item_id', $item->id)->exists();
    }
}