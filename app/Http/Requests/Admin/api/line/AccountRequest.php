<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Http\Requests\Admin\Base\Line\AccountRequest as BaseAccountRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountRequest extends BaseAccountRequest
{

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
