<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\Categorie;

trait FindCategorie
{
    public function findCategorie(int $id, bool $isHydrate = true): null|Categorie|array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome FROM categoria WHERE id = ?;");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateCategorie($result) : $result;
    }

    private function hydrateCategorie(array $categorieData): Categorie
    {
        $categorie = new Categorie($categorieData["nome"]);
        $categorie->setId($categorieData["id"]);

        return $categorie;
    }
}
