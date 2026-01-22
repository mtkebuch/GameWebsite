<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Review extends Model
{
    use SoftDeletes; 
    
    protected $fillable = [
        'user_id',
        'game_id',
        'rating',
        'comment',
    ];

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

  
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class)->withTrashed();
    }
}