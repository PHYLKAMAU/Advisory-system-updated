<?php

namespace App\Models;

use App\Traits\HasORMGettersAndSetters;
use Opis\ORM\Entity;
use Opis\ORM\IEntityMapper;

class Event extends Entity
{
    use HasORMGettersAndSetters;

    public static function mapEntity(IEntityMapper $mapper)
    {
        $mapper->assignable([
            
        ]);
    }
}


