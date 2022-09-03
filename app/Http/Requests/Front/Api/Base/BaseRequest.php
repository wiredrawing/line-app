<?php

namespace App\Http\Requests\Front\Api\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array|mixed
     */
    public function validationData()
    {
        $temp = array_merge(
            $this->input(),
            $this->route()->parameters(),
            $this->all()
        );
        logger()->info($temp);
        return $temp;
    }

    /**
     * API実行時エラーをapplication/jsonで返却する
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        logger()->error("errors ====> ", $errors);
        $response = [
            "status" => false,
            "response" => null,
            "errors" => $errors,
        ];
        // 例外レスポンスを作成し返却する
        $error_response = response()->json($response)->setStatusCode(400);
        throw new HttpResponseException($error_response);
    }
}
