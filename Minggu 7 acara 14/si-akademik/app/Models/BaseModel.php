<?php

require_once __DIR__ . '/../Core/Database.php';

class BaseModel
{
    protected $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connect();
    }
}