<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Produtos\Action\Domain\Model\Categorie;

trait FindCategorie
{
    public function findCategorie(int $id): Categorie
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategoria WHERE id = ?;");
        $stmt->bindValue(1, $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateCategorie($stmt->fetch());
    }

    private function hydrateCategorie(array $categorieData): Categorie
    {
        $categorie = new Categorie($categorieData["nome"]);
        $categorie->setId($categorieData["id"]);

        return $categorie;
    }
}
