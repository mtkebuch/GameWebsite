<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Support;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};