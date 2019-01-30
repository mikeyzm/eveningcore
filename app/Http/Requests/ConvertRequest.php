<?php

namespace App\Http\Requests;

use App\Rules\AllowedExt;
use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'source' => ['required', 'file', new AllowedExt(config('convert.allowed_extensions'))],
            'options.tempo' => ['required', 'numeric', 'between:1,2'],
            'options.pitch' => ['required', 'numeric', 'between:1,2'],
            'options.volume' => ['required', 'numeric', 'between:0.1,2'],
        ];
    }
}
