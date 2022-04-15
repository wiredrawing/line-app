<?php

namespace App\Http\Controllers\Admin\Api\Line;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Api\Line\MemberRequest;
use App\Models\LineMember;
use Illuminate\Http\JsonResponse;


class MemberController extends Controller
{

    private $errors = [];

    /**
     * 登録済みのLineメンバー一覧を返却する
     *
     * @param MemberRequest $request
     * @return JsonResponse
     */
    public function list(MemberRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $line_members = LineMember::where([
                "is_hidden" => Config("const.binary_type.on"),
            ])->get();

            $response = [
                "status" => true,
                "response" => $line_members,
                "errors" => null,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            $response = [
                "status" => false,
                "response" => null,
                "errors" => $this->errors,
            ];
            return response()->json($response);
        }
    }


    /**
     * 指定したline_member_idを持つメンバー情報を返却
     *
     * @param MemberRequest $request
     * @param int|null $line_member_id
     * @return JsonResponse
     */
    public function detail(MemberRequest $request, int $line_member_id = null): JsonResponse
    {
        try {
            $validated = $request->validated();

            $line_member = LineMember::findOrFail($validated["line_member_id"]);

            $response = [
                "status" => true,
                "response" => $line_member,
                "errors" => null,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            logger()->error($e);
            $response = [
                "status" => false,
                "response" => null,
                "errors" => $this->errors,
            ];
            return response()->json($response);
        }
    }
}
