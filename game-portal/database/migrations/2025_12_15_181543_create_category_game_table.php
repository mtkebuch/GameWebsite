<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('category_game', function (Blueprint $table) {
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['game_id','category_id']);
           
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('category_game');
    }
};
