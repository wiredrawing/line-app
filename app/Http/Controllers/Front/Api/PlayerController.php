<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\PlayerRequest;
use App\Models\Player;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class PlayerController extends Controller
{


    /**
     * 指定したplayer_idのプレイヤー情報を取得する
     *
     * @param PlayerRequest $request
     * @param int $player_id
     * @return JsonResponse
     */
    public function detail(PlayerRequest $request, int $player_id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info(print_r($validated_data, true));
            $player = Player::with([
                "line_member",
                "line_member.line_account",
                // "playing_game_titles",
                // "following_players",
                // "players_following_you",
                // "player_images.image",
            ])
                ->findOrFail($player_id);
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "player" => $player,
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }

    /**
     * 検索可能なゲームプレイヤー一覧を返却
     *
     * @param PlayerRequest $request
     * @return JsonResponse
     */
    public function search(PlayerRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $players = Player::with([
                "line_member",
            ])
                ->where([
                    "is_displayed" => Config("const.binary_type.on"),
                    "is_deleted" => Config("const.binary_type.off"),
                    "is_published" => Config("const.binary_type.on"),
                ])
                ->when(true, function ($query) use ($validated_data) {
                    // keywordによるワード検索の場合
                    if (isset($validated_data["keyword"]) && strlen($validated_data["keyword"])) {
                        return $query->where("description", "like", "%" . $validated_data["keyword"] . "%")
                            ->orWhere("nickname", "like", "%" . $validated_data["keyword"] . "%");
                    }
                    return $query;
                })
                ->get();
            logger()->info($validated_data);

            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "players" => $players,
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }

    /**
     * 指定したplayer_idのプレイヤー情報を更新する
     *
     * @param PlayerRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PlayerRequest $request, int $id): JsonResponse
    {
        try {
            $player = Player::with([
                "line_member",
                "line_member.line_account",
            ])
                ->findOrFail($id);
            $validated_data = $request->validated();

            logger()->info($validated_data);
            $result = $player->update($validated_data);
            if ($result !== true) {
                throw new Exception("Failed updating player info.");
            }
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "player" => $player,
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }
}
