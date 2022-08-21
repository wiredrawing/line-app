<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;



    public $fillable = [
        "from_player_id",
        "to_player_id",
        "matched_at",
    ];

}
