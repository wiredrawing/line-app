<?php

namespace App\Http\Requests\Admin\Base\Line;

use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = strtoupper($this->getMethod());
        $rules = [];
    }


    public function validationData()
    {
        $validate_data = array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters()
        );
        return $validate_data;
    }
}
