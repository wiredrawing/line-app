<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Player;
use Illuminate\Support\Facades\Route;

class GameTitleRequest extends BaseRequest
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
                if ($route_name === "front.api.top.gameTitle.search") {
                    // 現在,有効なゲームタイトル一覧を返却
                    $rules = [
                        "keyword" => [
                            "nullable",
                            "string",
                        ],
                    ];
                }
                break;

            case "POST":
                if ($route_name === "front.api.top.gameTitle.create") {
                    // 新規のゲームタイトル追加
                    $rules = [
                        "title" => [
                            "required",
                            "string",
                            "between:1,512",
                        ],
                        "platform_id" => [
                            "required",
                            "integer",
                        ],
                        "description" => [
                            "required",
                            "string",
                            "max:10000",
                        ],
                        "genre_id" => [
                            "required",
                            "integer",
                        ],
                    ];
                } else if ($route_name === "front.api.top.gameTitle.update") {
                    // 既存の登録ずみゲームタイトルの編集処理
                    $rules = [
                        "game_title_id" => [
                            "required",
                            "integer",
                        ],
                        "title" => [
                            "required",
                            "string",
                            "between:1,512",
                        ],
                        "platform_id" => [
                            "required",
                            "integer",
                        ],
                        "description" => [
                            "required",
                            "string",
                            "max:10000",
                        ],
                        "genre_id" => [
                            "required",
                            "integer",
                        ],
                    ];
                }
                break;
            default:
                // 該当しないルーティング
                break;
        }

        // リクエストには常にplayer固有のapi_tokenが必要
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
                    return $fail(":attributeが不正な値です.");
                }
                return true;
            },
        ];
        return $rules;
    }
}
