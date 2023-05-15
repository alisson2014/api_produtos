<?php

namespace Produtos\Action\Service;

use PDO;
use PDOStatement;

abstract class Connection
{
    protected readonly string $query;
    protected PDO $pdo;

    public function __construct(
        string $query,
        PDO $pdo
    ) {
        $this->query = $query;
        $this->pdo = $pdo;
    }

    final public function sqlConsult(): PDOStatement
    {
        $consult = ($this->pdo)->prepare($this->query);
        $consult->execute();
        return $consult;
    }
}
