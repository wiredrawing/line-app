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

            $json = [
                "statsu" => true,
                "code" => 200,
                "response" => [
                    "follower" => $follower,
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            $json = [
                "statsu" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }


    /**
     * @param FollowerRequest $request
     * @return JsonResponse
     */
    public function unfollow(FollowerRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $follower = Follower::where($validated_data)->destroy();
            $json = [
                "statsu" => true,
                "code" => 200,
                "response" => [
                    "follower" => $follower,
                ],
            ];
            return response()->json($json);
        } catch (Throwable $e) {
            $json = [
                "statsu" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }
}
