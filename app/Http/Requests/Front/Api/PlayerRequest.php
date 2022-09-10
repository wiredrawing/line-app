<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Player;
use Illuminate\Support\Facades\Route;

class PlayerRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        $route_name = Route::currentRouteName();
        switch (strtoupper($this->method())) {
            case "GET":
                if ($route_name === "front.api.top.player.detail") {
                    // 指定したplayer_idかつプレイヤーが公開中のプレイヤー情報を取得する
                    $rules = [
                        "player_id" => [
                            "required",
                            "integer",
                            function ($attribute, $value, $fail) {
                                logger()->info($attribute);
                                $player = Player::find($value);
                                if ($player === null) {
                                    return $fail(":attributeが不正なID値です.");
                                }
                                return true;
                            },
                        ],
                    ];
                } else if ($route_name === "front.api.top.player.search") {
                    $rules = [
                        "keyword" => [
                            "nullable",
                            "string",
                        ],
                    ];
                }
                break;
            case "POST":
                if ($route_name === "front.api.top.player.update") {
                    $rules = [
                        "player_id" => [
                            "required",
                            "integer",
                            function ($attribute, $value, $fail) {
                                logger()->info($attribute);
                                $player = Player::find($value);
                                if ($player === null) {
                                    return $fail(":attributeが不正なID値です.");
                                }
                                return true;
                            },
                        ],
                        "family_name" => [
                            "nullable",
                            "string",
                        ],
                        "middle_name" => [
                            "nullable",
                            "string",
                        ],
                        "given_name" => [
                            "nullable",
                            "string",
                        ],
                        "nickname" => [
                            "required",
                            "string",
                            "between:5,512",
                        ],
                        "gender_id" => [
                            "nullable",
                            "integer",
                        ],
                        "is_published" => [
                            "nullable",
                            "integer",
                        ],
                        "description" => [
                            "nullable",
                            "string",
                        ],
                        "memo" => [
                            "nullable",
                            "string",
                        ],
                    ];
                }
                break;
            default:
                break;

        }

        // トークンはどのリクエストでも必須とする
        $rules["api_token"] = [
            "required",
            "string",
            "min:1",
            function ($attribute, $value, $fail) {
                logger()->info($attribute);
                $player = Player::where([
                    "api_token" => $value,
                ])
                    ->get()
                    ->first();
                if ($player === null) {
                    return $fail(":attributeが不正なトークンです");
                }
                return true;
            },
        ];
        return $rules;
    }
}
