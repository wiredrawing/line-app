<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\PlayerImageRequest;
use App\Models\PlayerImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerImageController extends Controller
{


    /**
     * player画像のリソース作成
     * @param PlayerImageRequest $request
     * @return JsonResponse
     */
    public function create(PlayerImageRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            print_r($validated_data);
            $player_image = PlayerImage::create($validated_data);

            if ($player_image === null) {
                throw new \Exception("Failed registerting new player image.");
            }

            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "player_image" => $player_image
                ]
            ];
            return response()->json($json);
        } catch (\Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e,
            ];
            return response()->json($json);
        }
    }


    /**
     * 指定したuuidの画像リソースを削除する
     * @param PlayerImageRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function delete(PlayerImageRequest $request, string $id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info($validated_data);

            $player_image = PlayerImage::findOrFail($id);
            $result = $player_image->delete();
            var_dump($result);
            $json = [
                "status" => true,
                "code" => 200,
                "response" => $result,
            ];
            return response()->json($json);
        } catch (\Throwable $e) {
            logger()->error($e);
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e,
            ];
            return response()->json($json);
        }
    }


    /**
     * 指定したplayer_idのプレイヤーがアップロードした画像一覧を返却する
     *
     * @param PlayerImageRequest $request
     * @param int $player_id
     * @return JsonResponse
     */
    public function list(PlayerImageRequest $request, int $player_id): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info($validated_data);
            $player_images = PlayerImage::with([
                "image",
            ])->where([
                "player_id" => $player_id,
            ])->get();

            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "player_images" => $player_images,
                ],
            ];
            return response()->json($json);
        } catch (\Throwable $e) {
                logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e,
            ];
            return response()->json($json);
        }
    }
}
