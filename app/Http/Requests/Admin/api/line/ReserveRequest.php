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
