<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Domain\Repository\AddressRepo;
use Produtos\Action\Service\FindAddress;
use Produtos\Action\Service\TryAction;

final class AddressRepository implements AddressRepo
{
    use TryAction, FindAddress {
        FindAddress::findAddress as find;
    }
    
    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return Address[] */
    public function all(): ?array
    {
        $adressList = $this->pdo
            ->query("SELECT * FROM endereco ORDER BY id ASC")
            ->fetchAll();

        if (count($adressList) === 0) {
            return null;
        }
    
        return array_map(
            $this->hydrateAddress(...),
            $adressList
        );
    }
    public function add(Address $adress): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO endereco (id, cep, uf, cidade, bairro, logradouro, numero) 
                VALUES (NULL, :cep, :uf, :cidade, :bairro, :logradouro, :numero)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cep", $adress->cep, PDO::PARAM_INT);
        $stmt->bindValue(":uf", $adress->uf);
        $stmt->bindValue(":cidade", $adress->cidade);
        $stmt->bindValue(":bairro", $adress->bairro);
        $stmt->bindValue(":logradouro", $adress->logradouro);
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

    public function update(Address $adress): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE endereco 
                SET cep = :cep
                    uf = :uf
                    cidade = :cidade,
                    bairro = :bairro,
                    logradouro = :logradouro,
                    numero = :numero 
                WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cep", $adress->cep, PDO::PARAM_INT);
        $stmt->bindValue(":uf", $adress->uf);
        $stmt->bindValue(":cidade", $adress->cidade);
        $stmt->bindValue(":bairro", $adress->bairro);
        $stmt->bindValue(":logradouro", $adress->logradouro);
        $stmt->bindValue(":numero", $adress->numero);
        $stmt->bindValue(":id", $adress->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function findByCep(int $cep): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://viacep.com.br/ws/{$cep}/json/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $resArray = json_decode($response, true);

        return isset($resArray["cep"]) ? $resArray : null;
    }
}
