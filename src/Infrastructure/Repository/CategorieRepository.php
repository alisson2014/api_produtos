<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Domain\Repository\CategorieRepo;
use Produtos\Action\Service\FindCategorie;
use Produtos\Action\Service\TryAction;

final class CategorieRepository implements CategorieRepo
{
    use TryAction, FindCategorie {
        FindCategorie::findCategorie as find;
    }

    public function __construct(
        private PDO $pdo
    ) {
    }

    private function getPdo(): PDO
    {
        return $this->pdo;
    }

    /** @return Categorie[] */
    public function all(): array
    {
        $categorieList = $this->pdo
            ->query("SELECT * FROM subcategoria ORDER BY id ASC")
            ->fetchAll();

        if (count($categorieList) === 0) {
            return [];
        }

        return array_map(
            $this->hydrateCategorie(...),
            $categorieList
        );
    }

    public function add(Categorie $categorie): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO subcategoria (id, nome) VALUES (NULL, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categorie->nomeCategoria);
        $status = $this->tryAction($stmt, true);
        $result = $status["result"];

        if ($result) {
            $categorie->setId($status["id"]);
        }

        return $result;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM subcategoria WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result;
    }

    public function update(Categorie $categorie): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE subcategoria SET nome = :nome WHERE id = :id LIMIT 1;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nome", $categorie->nomeCategoria);
        $stmt->bindValue(":id", $categorie->id, PDO::PARAM_INT);
        $status = $this->tryAction($stmt);

        return $status;
    }
}
