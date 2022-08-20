<?php

namespace App\Http\Controllers\Admin\Line;

use App\Http\Controllers\Controller;
use App\Models\LineMember;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MemberController extends Controller
{





    /**
     * LINEログイン済みメンバー情報一覧
     *
     * @param Request $request
     * @return Application|Factory|View|void
     */
    public function index(Request $request)
    {
        try {
            $line_members = LineMember::with([
                "line_account",
            ])
            ->whereHas("line_account")
            ->get();

            return view("admin.line.member.index", [
                "line_members" => $line_members,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            logger()->error($e);
        }
    }



    /**
     * 指定したline_member_idの情報を取得する
     *
     * @param Request $request
     * @param int $line_member_id
     * @return Application|Factory|View|void
     */
    public function detail(Request $request, int $line_member_id = 0)
    {
        try {
            $line_member = LineMember::with([
                "line_account",
            ])
            ->whereHas("line_account")
            ->find($line_member_id);
            return view("admin.line.member.detail", [
                "line_member" => $line_member,
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            logger()->error($e);
        }
    }
}
