<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Player;
use Illuminate\Support\Facades\Route;

class FollowerRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
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

        if ($this->isMethod("get")) {
            $rules = [
                "player_id" => [
                    "required",
                    "integer",
                ],
                "api_token" => [
                    "required",
                    "string",
                    function ($attribute, $value, $fail) {
                        // 当該のapi_tokenキーで正しいplayer_idが指定されているかどうか
                        $player_id = $this->route()->parameter("player_id");
                        $player = Player::where(["api_token" => $value])->find($player_id);
                        if ($player === null) {
                            return $fail("指定したAPIトークンとプレイヤーIDがマッチしません");
                        }
                        return true;
                    }
                ]
            ];
            if ($route_name === "front.api.top.follower.followed") {
                // 当該ルーティング個別のルールを定義
            } else if ($route_name === "front.api.top.follower.following") {
                // 当該ルーティング個別のルールを定義
            } else if ($route_name === "front.api.top.follower.matched") {
                // 当該ルーティング個別のルールを定義
            }

        } else if ($this->isMethod("post")) {
            // playerのフォロー処理
            $rules = [
                "from_player_id" => [
                    "required",
                    "integer",
                ],
                "to_player_id" => [
                    "required",
                    "integer",
                ],
                "api_token" => [
                    "required",
                    "string",
                    function ($attribute, $value, $fail) {
                        // 当該のapi_tokenキーで正しいplayer_idが指定されているかどうか
                        $player_id = $this->route()->parameter("from_player_id");
                        $player = Player::where(["api_token" => $value])->find($player_id);
                        if ($player === null) {
                            return $fail("指定したAPIトークンとプレイヤーIDがマッチしません");
                        }
                        return true;
                    }
                ],
            ];
            if ($route_name === "front.api.top.follower.create") {
                // 個別ルールを定義
            } else if ($route_name === "front.api.top.follower.unfollow") {
                // 個別ルールを定義
            }
        }

        return $rules;
    }
}
