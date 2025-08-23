<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')
                ->constrained('boards')
                ->cascadeOnDelete()
                ->comment('boards.id (게시글)');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('users.id (좋아요 누른 사용자)');
            $table->string('ip_address', 45)->nullable()->comment('작성자 IP');
            $table->timestamps();

            $table->unique(['board_id','user_id'], 'uq_board_likes_board_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_likes');
    }
};
