<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardLike extends Model
{
    //
    protected $fillable = [
        "board_id",
        "user_id",
        "ip_address",
    ];
}
