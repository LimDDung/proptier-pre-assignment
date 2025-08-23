<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardComment extends Model
{
    //
    protected $fillable = [
        "board_id",
        "user_id",
        "parent_id",
        "depth",
        "path_bin",
        "content",
    ];
}
