<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Player;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class ImageRequest extends BaseRequest
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
     * @return array|array[]|string[][]
     */
    public function rules(): array
    {
        $route_name = Route::currentRouteName();
        $rules = [];
        switch(strtoupper($this->method())) {
            // GETリクエスト
            case "GET":
                if ($route_name === "front.api.top.image.show") {
                    // 指定した画像を表示する
                    $rules = [
                        "id" => [
                            "required",
                            "string",
                            Rule::exists("images", "id"),
                        ]
                    ];
                } else if ($route_name === "front.api.top.image.list") {
                    // 追加ルール
                }
                break;

            // POSTリクエスト
            case "POST":
                if ($route_name === "front.api.top.image.create") {
                    // 新規画像リソースを作成する
                    $rules = [
                        "filename" => [
                            "required",
                            "file",
                            "image:jpg,png,gif,jpeg",
                            // ファイルサイズは10MBをマックスとする
                            "max:10240"
                        ]
                    ];
                } else if ($route_name === "front.api.top.image.delete") {
                    // 既存画像リソースを削除する
                    $rules = [
                        "id" => [
                            "required",
                            "integer",
                            Rule::exists("images", "id"),
                        ]
                    ];
                }
                break;

            // 該当しないルーティング
            default:
                // 追加条件なし
                break;
        }

        if (null) {
            // playerのAPIトークンチェック
            $rules["api_token"] = [
                "required",
                "string",
                function ($attribute, $value, $fail) {
                    logger()->info($attribute);
                    $player_id = $this->validationData()["player_id"];
                    $player = Player::where([
                        "api_token" => $value,
                        "player_id" => $player_id,
                    ])
                        ->get()
                        ->first();
                    if ($player === null) {
                        return $fail("不正なplayerユーザーです");
                    }
                    return true;
                },
            ];
            $rules["player_id"] = [
                "required",
                "integer",
                Rule::exists("players", "id"),
            ];
        }
        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            "filename.max" => "最大アップ容量は10MBです",
            "filename.image" => "アップロード可能なファイルは画像ファイルのみです",
        ];
    }
}
