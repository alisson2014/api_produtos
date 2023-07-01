<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Domain\Repository\CategorieRepo;

class CategorieRepository implements CategorieRepo
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function all(): array
    {
        $categorieList = $this->pdo
            ->query("SELECT * FROM subcategoria ORDER BY id ASC")
            ->fetchAll();

        return array_map(
            $this->hydrateCategorie(...),
            $categorieList
        );
    }

    public function add(Categorie $categorie): bool
    {
        $sql = "INSERT INTO subcategoria (id, nome) VALUES (NULL, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categorie->categorieName);
        $status = $stmt->execute();

        if ($status) {
            $lastId = $this->pdo->lastInsertId();
            $categorie->setId(intval($lastId));
        }

        return $status;
    }

    public function remove(int $id): bool
    {
        $sql = "DELETE FROM subcategoria WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        return $result;
    }

    public function update(Categorie $categorie): bool
    {
        $sql = "UPDATE subcategoria SET nome = :nome WHERE id = :id LIMIT 1;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(":nome", $categorie->categorieName);
        $stmt->bindValue(":id", $categorie->id, PDO::PARAM_INT);
        $status = $stmt->execute();

        return $status;
    }

    public function find(int $id): Categorie
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategoria WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
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
