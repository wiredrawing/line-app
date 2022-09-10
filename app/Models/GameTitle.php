<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameTitle extends Model
{
    use HasFactory;


    public $fillable = [
        "title",
        "platform_id",
        "description",
        "genre_id",
        "is_displayed",
        "is_deleted",
        "created_by",
        "updated_by",
    ];

    /**
     * @return HasMany
     */
    public function playing_game_titles(): HasMany
    {
        return $this->hasMany(PlayingGameTitle::class, "game_title_id", "id");
    }
}
