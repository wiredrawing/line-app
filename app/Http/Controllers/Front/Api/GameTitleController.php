<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\GameTitleRequest;
use Illuminate\Http\Request;

class GameTitleController extends Controller
{


    /**
     * 有効なゲームタイトル一覧を返却する
     * @param GameTitleRequest $request
     * @return void
     */
    public function list(GameTitleRequest $request)
    {

    }
}
