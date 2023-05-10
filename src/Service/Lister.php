<?php

namespace Produtos\Action\Service;

use PDO;
use Produtos\Action\Model\Consult;

class Lister
{
    use Consult;
    private string $queryList;
    private PDO $pdo;

    public function __construct(
        string $queryList,
        PDO $pdo
    ) {
        $this->queryList = $queryList;
        $this->pdo = $pdo;
    }

    public function returnsData(): array
    {
        $json = [];

        $consult = $this->sqlConsult();

        while ($data = $consult->fetch(PDO::FETCH_OBJ)) {
            $json[] = $data;
        }

        return $json;
    }

    public function hasData(): bool
    {
        $consult = $this->sqlConsult();

        if ($consult && $consult->rowCount() !== 0) {
            return true;
        }

        return false;
    }
}
