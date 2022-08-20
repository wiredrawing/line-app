<?php

namespace App\Http\Requests\Front\Api;

use App\Models\GameTitle;
use App\Models\Player;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;

class GameTitleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            if ($route_name === "front.api.top.gameTitle.search") {
                // 現在,有効なゲームタイトル一覧を返却
                $rules = [
                    "keyword" => [
                        "nullable",
                        "string",
                    ]
                ];
            }
        } else if ($this->isMethod("post")) {
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
                    "id" => [
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
                ])->get()->first();
                if ($player === null) {
                    return $fail(":attributeが不正な値です.");
                }
                return true;
            }
        ];
        return $rules;
    }

    /**
     * API実行時エラーをapplication/jsonで返却する
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        logger()->error($errors);
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors,
        ];
        throw new HttpResponseException(response()->json($response), 422);
    }
}
