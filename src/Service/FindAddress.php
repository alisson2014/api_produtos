<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\Address;

trait FindAddress
{
    public function findAddress(int $id, bool $isHydrate = true): null|Address|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM endereco WHERE id = ?");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateAddress($result) : $result;
    }

    private function hydrateAddress(array $adressData): Address
    {
        $adress = new Address(
            $adressData["cep"],
            $adressData["uf"],
            $adressData["cidade"],
            $adressData["bairro"],
            $adressData["logradouro"],
            $adressData["numero"],
        );
        $adress->setId($adressData["id"]);

        return $adress;
    }
}
