<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->string('session_id', 64)->nullable()->after('uuid')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });
    }
};
