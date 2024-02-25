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
    public function all(bool $isHydrate = true): ?array
    {
        $sql = "SELECT 
                    vwe.id,
                    vwe.cep,
                    vwe.logradouro,
                    vwe.bairro,
                    vwe.cidade,
                    vwe.estado
                FROM vw_todos_enderecos vwe;";
        $addressList = $this->pdo->query($sql)->fetchAll();

        if (count($addressList) === 0) {
            return null;
        }
    
        return $isHydrate 
                ? array_map($this->hydrateAddress(...), $addressList) 
                : $addressList;
    }
    public function add(Address $address): bool
    {
        $idCity = $this->findCity($address->cidade);
        if(is_null($idCity)) {
            return false;
        }
        
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO endereco (cep, cidade_id, bairro, rua, numero) 
                VALUES (:cep, :cidade_id, :bairro, :rua, :numero)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cep", $address->cep);
        $stmt->bindValue(":cidade_id", $idCity);
        $stmt->bindValue(":bairro", $address->bairro);
        $stmt->bindValue(":rua", $address->logradouro);
        $stmt->bindValue(":numero", $address->numero);
        $status = $this->tryAction($stmt);
      
        $result = $status["result"];
        if ($result) {
            $address->setId($status["id"]);
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

    public function update(Address $address): bool
    {
        $idCity = $this->findCity($address->cidade);
        if(is_null($idCity)) {
            return false;
        }

        $this->pdo->beginTransaction();
        $sql = "UPDATE endereco 
                SET cep = :cep,
                    cidade_id = :cidade_id,
                    bairro = :bairro,
                    rua = :rua,
                    numero = :numero 
                WHERE id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":cep", $address->cep);
        $stmt->bindValue(":cidade_id", $idCity);
        $stmt->bindValue(":bairro", $address->bairro);
        $stmt->bindValue(":rua", $address->logradouro);
        $stmt->bindValue(":numero", $address->numero);
        $stmt->bindValue(":id", $address->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function findByCep(string $cep): ?array
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
