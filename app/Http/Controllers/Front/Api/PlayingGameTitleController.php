<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\PlayerImageRequest;
use App\Http\Requests\Front\Api\PlayingGameTitleRequest;
use App\Models\PlayingGameTitle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PlayingGameTitleController extends Controller
{


    /**
     * @param PlayingGameTitleRequest $request
     * @return JsonResponse
     */
    public function create(PlayingGameTitleRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info(print_r($validated_data, true));
            $playing_game_title = PlayingGameTitle::create($validated_data);

            $latest_playing_game_title = PlayingGameTitle::findOrFail($playing_game_title->id);
            $json = [
                "status" => true,
                "code" => 201,
                "response" => [
                    "playing_game_title" => $latest_playing_game_title,
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
     * @param PlayingGameTitleRequest $request
     * @param int $playing_game_title_id
     * @return JsonResponse
     */
    public function update(PlayingGameTitleRequest $request, int $playing_game_title_id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            $playing_game_title = PlayingGameTitle::findOrFail($playing_game_title_id);

            $result = $playing_game_title->update($validated_data);
            if ($result !== true) {
                throw new \Exception("Failed updating the record which you selected.");
            }
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "playing_game_title" => $playing_game_title,
                ],
            ];
            return response()->json($json);
        } catch (\Throwable $e) {
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json);
        }
    }

    /**
     * 指定したプレイ中のゲームタイトルを削除する
     * @param PlayingGameTitleRequest $request
     * @param int $playing_game_title_id
     * @return JsonResponse
     */
    public function delete(PlayingGameTitleRequest $request, int $playing_game_title_id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info(print_r($validated_data, true));
            $playing_game_title = PlayingGameTitle::findOrFail($playing_game_title_id);
            $result = $playing_game_title->delete();
            $json = [
                "status" => true,
                "code" => 200,
                "response" => $result,
            ];
            return response()->json($json);
        } catch (\Throwable $e) {
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
     * @param PlayingGameTitleRequest $request
     * @param int $player_id
     * @return JsonResponse
     */
    public function detail(PlayingGameTitleRequest $request, int $player_id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info(print_r($validated_data, true));

            $playing_game_titles = PlayingGameTitle::with([
                "game_title",
            ])
                ->where([
                    "player_id" => $validated_data["player_id"],
                ])
                ->get();
            logger()->info(print_r($playing_game_titles->toArray(), true));
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "playing_game_titles" => $playing_game_titles,
                ]
            ];
            return response()->json($json, 200);
        } catch (\Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json, 400);
        }
    }
}
