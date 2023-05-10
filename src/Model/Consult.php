<?php

namespace Produtos\Action\Model;

use PDOStatement;

trait Consult
{
    public function sqlConsult(): PDOStatement
    {
        $consult = $this->pdo->prepare($this->queryList);
        $consult->execute();
        return $consult;
    }
}
