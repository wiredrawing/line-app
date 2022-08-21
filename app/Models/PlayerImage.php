<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerImage extends Model
{
    use HasFactory;


    public $fillable = [
        "image_id",
        "player_id",
        "is_displayed",
    ];
}
