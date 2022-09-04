<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\ImageRequest;
use App\Models\Image;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ImageController extends Controller
{


    /**
     * URLパラメータに指定したimage idの画像を表示する
     *
     * @param ImageRequest $request
     * @param string $id
     * @return JsonResponse|BinaryFileResponse
     */
    public function show(ImageRequest $request, string $id)
    {
        try {
            $validated_data = $request->validated();
            logger()->info("validation_data ----> ", $validated_data);
            $image = Image::findOrFail($id);
            // 仮保存した画像をプロダクション用に本保存
            $production_path = "public/uploaded/{$image->created_at->format("Y")}/{$image->created_at->format("m")}/{$image->created_at->format("d")}/{$image->filename}.{$image->extension}";

            // 画像バイナリを返却する
            return response()->file(Storage::path($production_path));
        } catch (Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json)->setStatusCode(400);
        }
    }


    /**
     * 新規で画像リソースを作成する
     *
     * @param ImageRequest $request
     * @return JsonResponse
     */
    public function create(ImageRequest $request): JsonResponse
    {
        try {
            // アップロード時点を保存先ディレクトリとして使用する
            $uploaded_at = Carbon::now("Asia/Tokyo");
            $validated_data = $request->validated();
            $uploaded_file = $validated_data["filename"];

            // サーバー上にアップロードされたファイルを仮保存
            $temporary_path = "temporary/{$uploaded_at->format("Y")}/{$uploaded_at->format("m")}/{$uploaded_at->format("d")}";
            $path = $uploaded_file->store($temporary_path);

            // 保存したファイルから正しい拡張子を取得
            $file_on_server = new File(Storage::disk("local")->path($path));

            // ファイルのbinaryをhash化してファイル名とする
            $filename = hash("sha512", Storage::disk("local")->get($path));
            $extension = $file_on_server->extension();

            // 全くの同一ファイルはリソースの都合上アップさせない
            $image = Image::where([
                "filename" => $filename,
            ])->get()->first();
            if ($image === null) {
                // DB上に新規リソースの登録
                // 新規画像リソース
                $new_image = [
                    "extension" => $extension,
                    "filename" => $filename,
                ];
                $image = Image::create($new_image);
                if ($image === null) {
                    throw new Exception("画像の新規アップロードに失敗しました");
                }
                // 仮保存した画像をプロダクション用に本保存
                $production_path = "public/uploaded/{$image->created_at->format("Y")}/{$image->created_at->format("m")}/{$image->created_at->format("d")}/{$filename}.{$file_on_server->extension()}";
                $result = Storage::put($production_path, Storage::disk("local")->get($path));
                if ($result !== true) {
                    throw new Exception("仮ファイルから本ファイルへの移動に失敗しました");
                }
            }
            $json = [
                "status" => true,
                "code" => 201,
                "response" => [
                    "image" => $image,
                ],
            ];
            return response()->json($json)->setStatusCode(201);
        } catch (Throwable $e) {
            logger()->error($e);
            $json = [
                "status" => false,
                "code" => 400,
                "response" => $e->getMessage(),
            ];
            return response()->json($json)->setStatusCode(400);
        }
    }


    /**
     * 現在アップロードされている全画像リストを返却
     *
     * @param ImageRequest $request
     * @return JsonResponse
     */
    public function list(ImageRequest $request): JsonResponse
    {
        try {
            $validated_data = $request->validated();
            logger()->info($validated_data);
            $images = Image::all();
            $json = [
                "status" => true,
                "code" => 200,
                "response" => [
                    "images" => $images,
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
            return response()->json($json)->setStatusCode(400);
        }
    }
}
