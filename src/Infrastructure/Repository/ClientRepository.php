<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Client;
use Produtos\Action\Domain\Repository\ClientRepo;
use Produtos\Action\Service\TryAction;

final class ClientRepository implements ClientRepo
{
    use TryAction;

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return Client[] */
    public function all(): array
    {
        $clientList = $this->pdo
            ->query("SELECT * FROM orcamento ORDER BY data DESC")
            ->fetchAll();

        if (count($clientList) === 0) {
            return [];
        }

        return array_map(
            $this->hydrateClient(...),
            $clientList
        );
    }

    public function find(int $id): Client|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orcamento WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return [];
        }

        return $this->hydrateClient($result);
    }

    public function add(Client $client): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO orcamento VALUES (NULL, :nomeCliente, :data)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nomeCliente", $client->nomeCliente, PDO::PARAM_STR);
        $stmt->bindValue(":data", $client->dataOrcamento->format("Y-m-d"), PDO::PARAM_STR);
        $status = $this->tryAction($stmt, true);
        $result = $status["result"];

        if ($result) {
            $client->setId($status["id"]);
            return true;
        }

        return $result > 0;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();

        $sql = "DELETE FROM orcamento WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function update(Client $client): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE orcamento SET nomeCliente = :nomeCliente, data = :data WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nomeCliente", $client->nomeCliente, PDO::PARAM_STR);
        $stmt->bindValue(":data", $client->dataOrcamento->format("Y-m-d"), PDO::PARAM_STR);
        $stmt->bindValue(":id", $client->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    private function hydrateClient(array $clientData): Client
    {
        $date = new \DateTime($clientData["data"]);
        $client = new Client($clientData["nomeCliente"], $date);
        $client->setId($clientData["id"]);

        return $client;
    }
}
