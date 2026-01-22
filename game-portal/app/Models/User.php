<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role_id',  
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'created_by');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function library()
    {
        return $this->belongsToMany(Game::class, 'user_library')
                    ->withTimestamps()
                    ->withPivot('added_at');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Game::class, 'wishlists')
                    ->withTimestamps()
                    ->withPivot('added_at');
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }
}