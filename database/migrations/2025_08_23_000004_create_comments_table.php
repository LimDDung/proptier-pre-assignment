<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('board_id')
                ->constrained('boards')
                ->cascadeOnDelete()
                ->comment('boards.id (게시글)');

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('users.id (작성자)');

            // 자기참조 FK는 테이블 생성 후 별도로 붙일 예정
            $table->unsignedBigInteger('parent_id')->nullable()->comment('부모 댓글 id');

            $table->unsignedSmallInteger('depth')->default(0)->comment('댓글 뎁스');
            $table->binary('path_bin')->nullable()->comment("댓글 정렬");

            $table->text('content')->comment('댓글 내용');
            $table->timestamps();

            //$table->index(['board_id', 'path_bin'], 'idx_board_comments_board_path_bin');

        });


        // 자기참조 FK는 테이블이 만들어진 후에 추가
        Schema::table('board_comments', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('board_comments')
                ->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_comments');
    }
};
