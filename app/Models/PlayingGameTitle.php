<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlayingGameTitle extends Model
{
    use HasFactory;


    public $fillable = [
        "player_id",
        "game_title_id",
        // 当該ゲームのオフィシャルなID(ユーザー名や識別IDなど)
        "game_account_id",
        // やりこみ度
        "skill_level",
        // プレイの頻度(週何回?)
        "frequency",
        "memo",
    ];

    /**
     * ゲームタイトルの詳細情報
     * @return BelongsTo
     */
    public function game_title(): BelongsTo
    {
        return $this->belongsTo(GameTitle::class, "game_title_id", "id");
    }

    /**
     * プレイヤー情報
     * @return BelongsTo
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, "player_id", "id");
    }
}
