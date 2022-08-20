<?php

namespace App\Http\Controllers\Front\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Api\GameTitleRequest;

class GameTitleController extends Controller
{


    /**
     * 有効なゲームタイトル一覧を返却する
     * @param GameTitleRequest $request
     * @return void
     */
    public function search(GameTitleRequest $request)
    {

    }

    /**
     * @param GameTitleRequest $request
     * @return void
     */
    public function create(GameTitleRequest  $request)
    {

    }

    /**
     * @param GameTitleRequest $request
     * @param int $id
     * @return void
     */
    public function update(GameTitleRequest $request, int $id)
    {

    }
}
