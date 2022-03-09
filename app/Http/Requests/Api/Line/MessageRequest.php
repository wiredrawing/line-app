<?php

namespace App\Http\Requests\Api\Line;

use App\Http\Requests\Base\Line\MessageRequest as BaseMessageRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MessageRequest extends BaseMessageRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }





    /**
     * APIとしてリクエストされた場合の例外のスロー
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
