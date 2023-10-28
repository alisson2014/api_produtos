<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\Adress;

trait FindAdress
{
    public function findAdress(int $id, bool $isHydrate = true): null|Adress|array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM endereco WHERE id = ?");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateAdress($result) : $result;
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
}
