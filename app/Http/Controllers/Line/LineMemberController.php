<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineMemberController extends Controller
{


    /**
     * メインアカウントデータにログイン済みの場合
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        var_dump($request->user()
            ->toArray());
    }
}
