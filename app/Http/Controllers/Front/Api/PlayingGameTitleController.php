<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
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
            logger()->info($validated_data);
            $playing_game_title = PlayingGameTitle::create($validated_data);
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "playing_game_title" => $playing_game_title,
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
     * @param PlayingGameTitleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PlayingGameTitleRequest $request, int $id)
    {
        try {
            $validated_data = $request->validated();
            $playing_game_title = PlayingGameTitle::findOrFail($id);

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
}
