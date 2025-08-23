<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    // 회원 정보
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 게시글 좋아요 정보
    public function likes()
    {
        return $this->hasMany(BoardLike::class, 'board_id');
    }

}
