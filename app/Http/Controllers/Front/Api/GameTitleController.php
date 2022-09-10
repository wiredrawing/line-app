<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\GameTitleRequest;
use App\Models\GameTitle;
use App\Models\Player;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class GameTitleController extends Controller
{


    /**
     * 有効なゲームタイトル一覧を返却する
     *
     * @param GameTitleRequest $request
     * @return void
     */
    public function search(GameTitleRequest $request)
    {

    }

    /**
     * 新規のゲームタイトルを作成する
     *
     * @param GameTitleRequest $request
     * @return JsonResponse
     */
    public function create(GameTitleRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $game_title = GameTitle::create($validated_data);

            if ($game_title === null) {
                throw new Exception("新規ゲームタイトルの作成に失敗しました");
            }

            $game_title = GameTitle::findOrFail($game_title->id);

            $json = [
                "status" => true,
                "code" => 201,
                "response" => [
                    "game_title" => $game_title,
                ],
            ];
            return response()->json($json, 201);
        } catch (Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json, 400);
        }
    }

    /**
     * @param GameTitleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(GameTitleRequest $request, int $id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $game_title = GameTitle::findOrFail($id);

            $result = $game_title->update($validated_data);

            if ($result !== true) {
                throw new Exception("Failed updating game title which was selected by you.");
            }

            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "game_title" => $game_title,
                ],
            ];
            return response()->json($json, 200);
        } catch (Throwable $e) {
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json,400);
        }
    }
}
