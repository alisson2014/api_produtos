<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Adress;
use Produtos\Action\Domain\Repository\AdressRepo;
use Produtos\Action\Service\TryAction;

final class AdressRepository implements AdressRepo
{
    use TryAction;
    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return Adress[] */
    public function all(): ?array
    {
        $adressList = $this->pdo
            ->query("SELECT * FROM endereco ORDER BY id ASC")
            ->fetchAll();

        if (count($adressList) === 0) {
            return null;
        }
    
        return array_map(
            $this->hydrateAdress(...),
            $adressList
        );
    }

    public function find(int $id): ?Adress
    {
        $stmt = $this->pdo->prepare("SELECT * FROM endereco WHERE id = ?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $this->hydrateAdress($result);
    }

    public function add(Adress $adress): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO endereco (id, cidade, bairro, rua, numero) 
                VALUES (NULL, :cidade, : bairro, :rua, :numero)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cidade", $adress->cidade);
        $stmt->bindValue(":bairro", $adress->bairro);
        $stmt->bindValue(":rua", $adress->rua);
        $stmt->bindValue(":numero", $adress->numero);
        $status = $this->tryAction($stmt, true);
        $result = $status["result"];

        if ($result) {
            $adress->setId($status["id"]);
            return true;
        }

        return $result > 0;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();

        $sql = "DELETE FROM endereco WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function hasClient(int $id): bool
    {
        $hasClient = "SELECT * FROM cliente WHERE endereco = ?";
        $stmtHasClient = $this->pdo->prepare($hasClient);
        $stmtHasClient->bindValue(1, $id, PDO::PARAM_INT);
        $stmtHasClient->execute();
        $rowCountStmt = $stmtHasClient->rowCount();

        return $rowCountStmt > 0;
    }

    private function hydrateAdress(array $adressData): Adress
    {
        $adress = new Adress(
            $adressData["cidade"],
            $adressData["bairro"],
            $adressData["rua"],
            $adressData["numero"],
        );
        $adress->setId($adressData["id"]);

        return $adress;
    }

    public function update(Adress $adress): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE endereco 
                SET cidade = :cidade,
                    bairro = :bairro,
                    rua = :rua,
                    numero = :numero 
                WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cidade", $adress->cidade);
        $stmt->bindValue(":bairro", $adress->bairro);
        $stmt->bindValue(":rua", $adress->rua);
        $stmt->bindValue(":numero", $adress->numero);
        $stmt->bindValue(":id", $adress->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }
}
