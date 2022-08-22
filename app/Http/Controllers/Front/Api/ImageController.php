<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\ImageRequest;
use Illuminate\Http\Request;
use Throwable;

class ImageController extends Controller
{


    /**
     * URLパラメータに指定したimage idの画像を表示する
     *
     * @param ImageRequest $request
     * @param $string
     * @return void
     */
    public function show(ImageRequest $request, string $id)
    {
        try {

        } catch (Throwable $e) {

        }

    }


    /**
     * 新規で画像リソースを作成する
     *
     * @param Request $request
     * @return void
     */
    public function create(ImageRequest $request)
    {
        try {

        } catch (Throwable $e) {

        }
    }


}
