<?php

namespace App\Http\Requests\Front\Api;

use App\Http\Requests\Front\Api\Base\BaseRequest;
use App\Models\Follower;
use App\Models\Player;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

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

        switch (strtoupper($this->method())) {

            case "GET":
                if ($route_name === "front.api.top.follower.followed") {
                    // 当該ルーティング個別のルールを定義
                    $rules = [
                        "to_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ],
                        "api_token" => [
                            "required",
                            "string",
                            function ($attribute, $value, $fail) {
                                // 当該のapi_tokenキーで正しいplayer_idが指定されているかどうか
                                $player_id = $this->route()->parameter("to_player_id");
                                $player = Player::where(["api_token" => $value])->find($player_id);
                                if ($player === null) {
                                    return $fail("指定したAPIトークンとプレイヤーIDがマッチしません");
                                }
                                return true;
                            }
                        ]
                    ];
                } else if ($route_name === "front.api.top.follower.following") {
                    // 当該ルーティング個別のルールを定義
                    $rules = [
                        "from_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
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
                        ]
                    ];
                } else if ($route_name === "front.api.top.follower.matched") {
                    // 当該ルーティング個別のルールを定義
                    $rules = [
                        "from_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
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
                        ]
                    ];
                }
                break;
            case "POST":
                if ($route_name === "front.api.top.follower.create") {
                    // playerのフォロー処理
                    $rules = [
                        "from_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ],
                        "to_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                            function ($attribute, $value, $fail) {
                                // 対象の to_player_id ユーザーを未フォローかどうかをチェック
                                $from_player_id = $this->input("from_member_id");
                                $to_player_id = $value;
                                $follower = Follower::where([
                                    "from_player_id" => $from_player_id,
                                    "to_player_id" => $to_player_id,
                                ])
                                    ->get()
                                    ->first();
                                if ($follower !== null) {
                                    $fail("プレイヤーID:{$value}を既にフォロー中です.");
                                }
                            }
                        ],
                        "api_token" => [
                            "required",
                            "string",
                            function ($attribute, $value, $fail) {
                                logger()->info($attribute);
                                // 当該のapi_tokenキーで正しいplayer_idが指定されているかどうか
                                $player_id = $this->input("from_player_id");
                                $player = Player::where(["api_token" => $value])->find($player_id);
                                if ($player === null) {
                                    return $fail("指定したAPIトークンとプレイヤーIDがマッチしません");
                                }
                                return true;
                            }
                        ],
                    ];
                } else if ($route_name === "front.api.top.follower.delete") {
                    // プレイヤーのフォロー解除
                    $rules = [
                        "from_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                        ],
                        "to_player_id" => [
                            "required",
                            "integer",
                            Rule::exists("players", "id"),
                            function ($attribute, $value, $fail) {
                                // 対象のプレイヤーをフォローしているかどうか?
                                $from_player_id = $this->input("from_player_id");
                                $to_player_id = $value;
                                $follower = Follower::where([
                                    "from_player_id" => $from_player_id,
                                    "to_player_id" => $to_player_id,
                                ])
                                    ->get()
                                    ->first();
                                if ($follower === null) {
                                    $fail("プレイヤーID:{$value}がフォローされていないためフォロー解除できません");
                                }
                            }
                        ],
                        "api_token" => [
                            "required",
                            "string",
                            function ($attribute, $value, $fail) {
                                logger()->info($attribute);
                                // 当該のapi_tokenキーで正しいplayer_idが指定されているかどうか
                                $player_id = $this->input("from_player_id");
                                $player = Player::where(["api_token" => $value])->find($player_id);
                                if ($player === null) {
                                    return $fail("指定したAPIトークンとプレイヤーIDがマッチしません");
                                }
                                return true;
                            }
                        ],
                    ];
                }
                break;
        }
        return $rules;
    }
}
