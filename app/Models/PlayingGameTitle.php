<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlayingGameTitle extends Model
{
    use HasFactory;


    public $fillable = [
        "player_id",
        "game_title_id",
        // やりこみ度
        "skill_level",
        // プレイの頻度(週何回?)
        "frequency",
        "memo",
    ];

    /**
     * ゲームタイトルの詳細情報
     * @return HasOne
     */
    public function game_title(): HasOne
    {
        return $this->hasOne(GameTitle::class, "id", "game_title_id");
    }

    /**
     * プレイヤー情報
     * @return HasOne
     */
    public function player(): HasOne
    {
        return $this->hasOne(Player::class, "id", "player_id");
    }
}
