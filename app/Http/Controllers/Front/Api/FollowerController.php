<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\FollowerRequest;
use App\Models\Follower;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Throwable;

class FollowerController extends Controller
{


    /**
     * 指定したplayerのマッチング済みプレイヤー一覧を返却する
     *
     * @return void
     */
    public function matched(FollowerRequest $request, Integer $player_id)
    {
        try {

        } catch (Throwable $e) {

        }
    }


    /**
     * 指定したplayerをフォローしているplayer一覧を取得
     *
     * @return void
     */
    public function followed(FollowerRequest $request, Integer $player_id)
    {
        try {

        } catch (Throwable $e) {

        }
    }


    /**
     * 指定したplayerがフォロー中のplayer一覧を返却する
     *
     * @return void
     */
    public function folllowing(FollowerRequest $request, Integer $player_id)
    {
        try {

        } catch (Throwable $e) {

        }
    }


    /**
     * @param FollowerRequest $request
     * @return JsonResponse
     */
    public function create(FollowerRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();

            $follower = Follower::create($validated_data);
            if ($follower === null) {
                throw new Exception("プレイヤーのフォローに失敗しました");
            }
            $follower = Follower::findOrFail($follower->id);
            $json = [
                "status" => true,
                "code" => 201,
                "response" => [
                    "follower" => $follower,
                ],
            ];
            logger()->info(print_r($json, true));
            return response()->json($json, 201);
        } catch (Throwable $e) {
            logger()->error($e);
            $json = [
                "statsu" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }


    /**
     * 指定したプレイヤーの組み合わせのフォロー関係を削除する
     *
     * @param FollowerRequest $request
     * @return JsonResponse
     */
    public function delete(FollowerRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info(print_r($validated_data, true));
            $follower = Follower::where([
                "from_player_id" => $validated_data["from_player_id"],
                "to_player_id" => $validated_data["to_player_id"],
            ]);
            if ($follower->count() !== 1) {
                throw new Exception("システム上でDBの整合性不正が起きています");
            }
            $deleted_follower_id = $follower->get()->first()->id;
            // マッチしたフォローレコードを削除する
            $deleted = Follower::destroy($deleted_follower_id);
            if ($deleted !== 1) {
                // 削除されるレコード件数は設計上は必ず1件のみ
                throw new \Exception("レコードの削除に失敗しました");
            }
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "follower" => [
                        "id" => $deleted_follower_id,
                    ],
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            $json = [
                "statsu" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json, 400);
        }
    }
}
