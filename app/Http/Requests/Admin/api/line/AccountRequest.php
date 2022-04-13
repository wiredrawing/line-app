<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Http\Requests\Admin\Base\Line\AccountRequest as BaseAccountRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountRequest extends BaseAccountRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  *
    //  * @return bool
    //  */
    // public function authorize()
    // {
    //     return true;
    // }

    // /**
    //  * Get the validation rules that apply to the request.
    //  *
    //  * @return array
    //  */
    // public function rules()
    // {
    //     $rules = [];
    //     $route_name = Route::currentRouteName();

    //     if ($this->isMethod("post")) {
    //     } elseif ($this->isMethod("get")) {
    //     }
    //     return $rules;
    // }




    // /**
    //  * バリデーション対象のデータ
    //  *
    //  * @return array
    //  */
    // public function validationData():array
    // {
    //     $validation_data = array_merge(
    //         $this->all(),
    //         $this->input(),
    //         $this->route()->parameters(),
    //     );
    //     return $validation_data;
    // }

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
