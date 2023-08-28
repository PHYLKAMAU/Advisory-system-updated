<?php

use App\Models\Department;
use App\Models\Event;
use App\Models\Meeting;
use App\Models\School;
use App\Models\User;
use Opis\ORM\EntityManager;
use Opis\Database\Connection;
use Opis\Database\Database;

$GLOBALS['CONN'] = new Connection(
    "mysql:host=localhost;port=3306;dbname=student_advisory_system", 
    "root", 
    ""
);
$GLOBALS['CONN']->persistent();

$GLOBALS['DB'] = new Database($GLOBALS['CONN']);
$GLOBALS['ORM'] = new EntityManager($GLOBALS['CONN']);

$GLOBALS['ORM_USER'] = $GLOBALS['ORM'](User::class);
$GLOBALS['ORM_MEETING'] = $GLOBALS['ORM'](Meeting::class);
$GLOBALS['ORM_EVENT'] = $GLOBALS['ORM'](Event::class);
$GLOBALS['ORM_DEPARTMENT'] = $GLOBALS['ORM'](Department::class);
$GLOBALS['ORM_SCHOOL'] = $GLOBALS['ORM'](School::class);
