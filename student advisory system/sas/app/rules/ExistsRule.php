<?php

namespace App\Rules;

use PDO;
use Rakit\Validation\Rule;

class ExistsRule extends Rule
{
    protected $message = ":attribute :value does not exist";

    protected $fillableParams = ['table', 'column'];

    protected $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;port=3306;dbname=student_advisory_system", "root", "");
    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');

        // do query
        $stmt = $this->pdo->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // true for valid, false for invalid
        return intval($data['count']) > 0;
    }
}
