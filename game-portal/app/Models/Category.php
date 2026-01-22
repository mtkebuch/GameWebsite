<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'category_game')
            ->whereNull('games.deleted_at');
    }
    
    public function allGames()
    {
        return $this->belongsToMany(Game::class, 'category_game')
            ->withTrashed();
    }
}