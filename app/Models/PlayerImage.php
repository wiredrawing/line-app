<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlayerImage extends Model
{
    use HasFactory;


    public $fillable = [
        "image_id",
        "player_id",
        "is_displayed",
    ];


    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, "id", "image_id");
    }

    /**
     * @return HasOne
     */
    public function player(): HasOne
    {
        return $this->hasOne(Player::class, "id", "player_id");
    }

}
