<?php

namespace App\Rules;

use Rakit\Validation\Rule;

class StringRule extends Rule
{
    protected $message = ":attribute :value is not a string";

    public function check($value): bool
    {
       return is_string($value);
    }
}
