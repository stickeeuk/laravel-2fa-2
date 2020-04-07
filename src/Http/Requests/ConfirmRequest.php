<?php

namespace Stickee\Laravel2fa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Stickee\Laravel2fa\Rules\ValidCode;

class ConfirmRequest extends FormRequest
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
        $driverName = last(explode('/', $this->route()->action['prefix']));

        return [
            'code' => [
                'required',
                'string',
                app()->makeWith(ValidCode::class, ['driver' => $driverName])
            ],
        ];
    }
}
