<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use Produtos\Action\Domain\Model\City;
use Produtos\Action\Service\{TryAction, FindState};
use Produtos\Action\Domain\Repository\ICityRepository;

final class CityRepository implements ICityRepository
{
    use TryAction, FindState;

    public function __construct(
        private \PDO $pdo
    ) {
    }

    public function all(bool $isHydrate = true): ?array
    {
        $cityList = $this->pdo
            ->query("SELECT id, nome, estado_id FROM cidade ORDER BY id")
            ->fetchAll();

        if (count($cityList) === 0) {
            return null;
        }

        return $isHydrate
            ? array_map($this->hydrateCity(...), $cityList)
            : $cityList;
    }

    public function find(int $id, bool $isHydrate = true): null|City|array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, estado_id FROM cidade WHERE id = ?;");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateCity($result) : $result;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();

        $sql = "DELETE FROM cidade WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result["result"] > 0;     
    }

    private function hydrateCity(array $data): City
    {
        $city = new City($data["nome"], $this->findState($data["estado_id"]));
        $city->setId($data["id"]);

        return $city;
    }
}
