<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\PlayerImage;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class PlayerImageRequest extends BaseRequest
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
        $route_name = Route::currentRouteName();
        $rules = [];

        switch (strtoupper($this->method())) {
            case "GET":
                // resourceの取得のみ
                if ($route_name === "front.api.top.player.image.list") {
                    $rules = [
                        "player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ]
                    ];
                }
                break;

            case "POST":
                // resourceの更新
                if ($route_name === "front.api.top.player.image.create") {
                    $rules = [
                        "image_id" => [
                            "required",
                            "string",
                            Rule::exists("images", "id"),
                        ],
                        "player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                            // player_idとimage_idの同一の組み合わせは存在させない
                            function($attribute, $value, $fail) {
                                logger()->info($attribute);
                                logger()->info($value);
                                $player_image = PlayerImage::where([
                                    "image_id" => $this->input("image_id"),
                                    "player_id" => $this->input("player_id"),
                                ])->get()->first();
                                if($player_image !== null) {
                                    return $fail("既に同一の画像がアップロードされています");
                                }
                                return true;
                            }
                        ]
                    ];
                } else if ($route_name === "front.api.top.player.image.delete") {
                    $rules = [
                        "id" => [
                            "required",
                            "string",
                            Rule::exists("player_images", "id"),
                        ]
                    ];
                }
                break;
            default:
                break;
        }

        return $rules;
    }
}
