<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\State;
use Produtos\Action\Domain\Repository\StateRepo;
use Produtos\Action\Service\TryAction;

final class StateRepository implements StateRepo
{
    use TryAction;

    public function __construct(
        private PDO $pdo
    ) {
    }

    public function all(bool $isHydrate = true, string $only = ""): ?array
    {
        $sql = "SELECT id, uf, descricao, ativo FROM estado";

        if($only === "s" || $only === "n") {
            $sql .= " WHERE ativo = '{$only}' ";
        }

        $stateList = $this->pdo->query($sql)->fetchAll();

        if (count($stateList) === 0) {
            return null;
        }

        return $isHydrate 
            ? array_map($this->hydrateState(...), $stateList)
            : $stateList;
    }

    public function patch(State $state): bool
    {
        $this->pdo->beginTransaction();

        $active = $state->active ? "s" : "n";

        $sql = "UPDATE estado SET ativo = '{$active}' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $state->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function find(int $id, bool $isHydrate = true): null|State|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estado WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
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
