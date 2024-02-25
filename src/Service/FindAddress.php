<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\Address;

trait FindAddress
{
    public function findAddress(int $id, bool $isHydrate = true): null|Address|array
    {
        $sql = "SELECT 
                    vwe.id,
                    vwe.cep,
                    vwe.logradouro,
                    vwe.bairro,
                    vwe.cidade,
                    vwe.estado,
                    vwe.sigla_estado AS uf
                FROM vw_todos_enderecos vwe
                WHERE id = ?;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateAddress($result) : $result;
    }

    public function findCity(string $city): ?int 
    {
        $sql = "SELECT id FROM cidade WHERE nome = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $city);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $result["id"];
    }

    private function hydrateAddress(array $adressData): Address
    {
        $adress = new Address(
            $adressData["cep"],
            $adressData["uf"],
            $adressData["cidade"],
            $adressData["bairro"],
            $adressData["logradouro"]
        );
        $adress->setId($adressData["id"]);

        return $adress;
    }
}
