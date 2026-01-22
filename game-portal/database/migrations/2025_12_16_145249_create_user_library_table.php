<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_library', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->timestamp('added_at')->useCurrent();
            $table->softDeletes(); 
            $table->timestamps();
            
            
            $table->unique(['user_id', 'game_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_library');
    }
};