<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayingGameTitle extends Model
{
    use HasFactory;


    public  $fillable = [
        "player_id",
        "game_title_id",
        "skill_level",
        "frequency",
    ];
}
