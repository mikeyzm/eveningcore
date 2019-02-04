<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedExt implements Rule
{
    public $extensions;

    public function __construct($extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value->getClientOriginalExtension(), $this->extensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.mimes', ['values' => implode(',', $this->extensions)]);
    }
}
