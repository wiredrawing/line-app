<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Models\LineAccount;
use App\Http\Requests\Admin\Base\Line\ReserveRequest as BaseReserveRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReserveRequest extends BaseReserveRequest
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

//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array
//     */
//    public function rules()
//    {
//        $rules = [];
//        $current_route = Route::currentRouteName();
//
//        if ($this->isMethod("post")) {
//
//        } elseif ($this->isMethod("get")) {
//
//        }
//
//
//        return $rules;
//    }


//    /**
//     * バリデーション対象のデータをここで構築する
//     *
//     * @return array
//     */
//    public function validationData():array
//    {
//        $validation_data = array_merge(
//            $this->all(),
//            $this->input(),
//            $this->route()->parameters(),
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
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors
        ];
        throw new HttpResponseException(response()->json($response), 422);
    }
}
