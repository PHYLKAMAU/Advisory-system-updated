<?php

namespace App\Models;

use App\Traits\HasORMGettersAndSetters;
use Opis\ORM\Entity;
use Opis\ORM\IEntityMapper;
use Opis\ORM\IMappableEntity;

class School extends Entity implements IMappableEntity
{
    use HasORMGettersAndSetters;

    public static function mapEntity(IEntityMapper $mapper)
    {
        $mapper->assignable([
            'name',
        ]);
    }
}


