<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Follower extends Model
{
    use HasFactory;



    public $fillable = [
        "from_player_id",
        "to_player_id",
        "matched_at",
    ];


    /**
     * @return HasOne
     */
    public function from_player(): HasOne
    {
        return $this->hasOne(Player::class, "id", "from_player_id");
    }

    /**
     * @return HasOne
     */
    public function to_player(): HasOne
    {
        return $this->hasOne(Player::class, "id", "to_player_id");
    }
}
