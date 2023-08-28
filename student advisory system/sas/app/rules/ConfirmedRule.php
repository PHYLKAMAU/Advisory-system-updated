<?php

namespace App\Rules;

use Rakit\Validation\Rule;

class ConfirmedRule extends Rule
{
    protected $message = ":attribute do no match";

    public function check($value): bool
    {
       return trim($value) == $this->validation->getAttribute("{$value}_confirmation")->getValue();
    }
}
