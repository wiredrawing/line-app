<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Player;
use App\Models\PlayingGameTitle;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlayingGameTitleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $route_name = Route::currentRouteName();

        if ($this->isMethod("get")) {

            if ($route_name === "front.api.top.playingGameTitle.detail") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                        Rule::exists("players", "id"),
                    ],
                ];
            }

        } else if ($this->isMethod("post")) {

            if ($route_name === "front.api.top.playingGameTitle.create") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                        Rule::exists("players", "id"),
                    ],
                    "game_title_id" => [
                        "required",
                        "integer",
                        Rule::exists("game_titles", "id"),
                    ],
                    "skill_level" => [
                        "required",
                        "integer",
                    ],
                    "frequency" => [
                        "required",
                        "integer",
                    ],
                ];
            } else if ($route_name === "front.api.top.playingGameTitle.update") {
                $rules = [
                    // playing_game_titlesテーブルのプライマリキー
                    "playing_game_title_id" => [
                        "required",
                        "integer",
                        Rule::exists("playing_game_titles", "id"),
                    ],
                    "player_id" => [
                        "required",
                        "integer",
                        Rule::exists("players", "id"),
                    ],
                    "game_title_id" => [
                        "required",
                        "integer",
                        Rule::exists("game_titles", "id"),
                    ],
                    "skill_level" => [
                        "required",
                        "integer",
                    ],
                    "frequency" => [
                        "required",
                        "integer",
                    ],
                ];
            } else if ($route_name === "front.api.top.playingGameTitle.delete") {
                $rules = [
                    "player_id" => [
                        "required",
                        "integer",
                        Rule::exists("playing_game_titles", "id")
                    ]
                ];
            }
        }

        // frontAPIのリクエストには api_token on playersテーブルが必須
        $rules["api_token"] = [
            "required",
            "string",
            function ($attribute, $value, $fail) {
                // URLにわたされたplayer_idを取得
                $validation_data = $this->validationData();
                if (isset($validation_data["player_id"])) {
                    logger()->info($attribute);
                    $player = Player::where([
                        "api_token" => $value,
                    ])
                        ->find($validation_data["player_id"]);
                    if ($player === null) {
                        return $fail(":attributeは不正なトークンです");
                    }
                    return true;
                }
                return $fail("プレイヤーIDを指定して下さい.");
            },
        ];
        return $rules;
    }
}
