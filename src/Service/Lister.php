<?php

namespace Produtos\Action\Service;

use PDO;

class Lister extends Connection
{
    public function __construct(
        string $query,
        PDO $pdo
    ) {
        parent::__construct($query, $pdo);
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
