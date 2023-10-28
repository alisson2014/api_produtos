<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Domain\Model\ClientS;
use Produtos\Action\Service\FindAddress;
use Produtos\Action\Service\TryAction;

final class ClientSRepository
{
    use TryAction, FindAddress;

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return ?ClientS[] */
    public function all(): ?array
    {
        $clientList = $this->pdo
            ->query("SELECT * FROM cliente ORDER BY id ASC")
            ->fetchAll();

        if (count($clientList) === 0) {
            return null;
        }
    
        return array_map(
            $this->hydrateClient(...),
            $clientList
        );
    }

    public function find(int $id): ?ClientS
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE id = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $this->hydrateClient($result);
    }

    public function add(ClientS $client): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO cliente (id, nomeCliente, cpf, endereco) 
                VALUES (NULL, :nomeCliente, :cpf, :endereco)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nomeCliente", $client->nomeCliente);
        $stmt->bindValue(":cpf", $client->cpf);
        $stmt->bindValue(":endereco", $client->endereco, PDO::PARAM_INT);
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

        $sql = "DELETE FROM cliente WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    private function hydrateClient(array $clientData): ClientS
    {
        $adress = new ClientS(
            $clientData["nomeCliente"],
            $clientData["cpf"],
            $this->findAddress($clientData["id"])
        );
        $adress->setId($clientData["id"]);

        return $adress;
    }

    public function update(ClientS $client): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE cliente 
                SET nomeCliente = :nomeCliente,
                    cpf = :cpf,
                    endereco = :endereco
                WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nomeCliente", $client->nomeCliente);
        $stmt->bindValue(":cpf", $client->cpf);
        $stmt->bindValue(":endereco", $client->idEndereco, PDO::PARAM_INT);
        $stmt->bindValue(":id", $client->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }
}
