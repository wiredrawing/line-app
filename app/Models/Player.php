<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;


    public $fillable = [
        "line_member_id",
        "family_name",
        "middle_name",
        "given_name",
        "nickname",
        "email",
        "description",
        "is_displayed",
        "is_deleted",
        "gender_id",
        "is_published",
        "api_token",
    ];

    public $appends = [
        // 当該playerとマッチしている他player一覧を取得する
        "players_matching_with_you",
    ];

    /**
     * @return HasOne
     */
    public function line_member(): HasOne
    {
        return $this->hasOne(LineMember::class, "id", "line_member_id");
    }

    /**
     * 当該のプレイヤーが現在プレイ中のゲームタイトル情報を返却する
     * @return HasMany
     */
    public function playing_game_titles(): HasMany
    {
        return $this->hasMany(PlayingGameTitle::class, "player_id", "id");
    }


    /**
     * 当該のplayerがフォロー中のプレイヤーを取得する
     * @return HasMany
     */
    public function following_players(): HasMany
    {
        return $this->hasMany(Follower::class, "from_player_id", "id");
    }

    /**
     * 当該のプレイヤーをフォローしているプレイヤー
     * @return HasMany
     */
    public function players_following_you(): HasMany
    {
        return $this->hasMany(Follower::class, "to_player_id", "id");
    }

    /**
     * 当該playerとマッチングしているユーザー一覧を取得
     */
    public function getPlayersWithYouAttribute()
    {

    }
}
