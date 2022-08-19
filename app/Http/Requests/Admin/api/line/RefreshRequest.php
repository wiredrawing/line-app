<?php

namespace App\Http\Requests\Admin\Api\Line;

use App\Http\Requests\Admin\Base\Line\RefreshRequest as BaseRefreshRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Validation\Rule;
// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Route;

class RefreshRequest extends BaseRefreshRequest
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
