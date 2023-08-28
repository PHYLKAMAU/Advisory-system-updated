<?php

namespace App\Models;

use App\Traits\HasORMGettersAndSetters;
use Opis\ORM\Entity;
use Opis\ORM\IEntityMapper;
use Opis\ORM\IMappableEntity;

class User extends Entity implements IMappableEntity
{
    use HasORMGettersAndSetters;
    
    public static function mapEntity(IEntityMapper $mapper)
    {
        $mapper->relation('departments')->hasOne(Department::class);
        $mapper->relation('schools')->hasOne(School::class);

        $mapper->setter('password', function(string $value){
            return password_hash($value, PASSWORD_BCRYPT);
        });

        $mapper->setter('registration_number', function(string $value){
            return strtoupper($value);
        });

        $mapper->assignable([
            'email',
            'password',
            'role',
            'registration_number',
            'joined_on',
            'lastaccess',
            'department_id',
            'school_id',
        ]);
    }
}
