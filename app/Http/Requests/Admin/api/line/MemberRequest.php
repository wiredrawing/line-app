<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Http\Requests\Admin\Base\Line\MemberRequest as BaseMemberRequest;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;

class MemberRequest extends BaseMemberRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     *
//     * @return bool
//     */
//    public function authorize()
//    {
//        return true;
//    }
//
//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array
//     */
//    public function rules(): array
//    {
//        return [];
//    }
//
//    /**
//     * バリデーション対象の変数
//     *
//     * @return array
//     */
//    public function validationData():array
//    {
//        $validation_data = array_merge(
//            $this->all(),
//            $this->input(),
//            $this->route()->parameters()
//        );
//        logger()->info($validation_data);
//        return $validation_data;
//    }

    /**
     * API実行時エラーをapplication/jsonで返却する
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        logger()->error($errors);
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors,
        ];
        throw new HttpResponseException(response()->json($response), 422);
    }
}
