<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\State;

trait FindState
{
    public function findState(int $id, bool $isHydrate = true): null|State|array
    {
        $stmt = $this->pdo->prepare("SELECT id, descricao, uf, ativo FROM estado WHERE id = ?;");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateState($result) : $result;
    }

    private function hydrateState(array $data): State
    {
        $state = new State($data["uf"], $data["descricao"]);
        $state->setId($data["id"]);

        return $state;
    }
}
