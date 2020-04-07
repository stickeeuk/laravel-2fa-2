<?php

namespace Stickee\Laravel2fa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Stickee\Laravel2fa\Rules\ValidCode;

class AuthenticateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'laravel_2fa_code' => [
                'required',
                'string',
                app(ValidCode::class)
            ],
        ];
    }
}
