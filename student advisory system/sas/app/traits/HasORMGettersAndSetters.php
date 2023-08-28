<?php

namespace App\Traits;

trait HasORMGettersAndSetters
{    
    public function __get($name)
    {
        return $this->orm()->getColumn($name);
    }

    public function __set($name, $value)
    {
        $this->orm()->setColumn($name, $value);
    }
}
