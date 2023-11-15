<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Domain\Repository\CategorieRepo;
use Produtos\Action\Service\{FindCategorie, TryAction};

final class CategorieRepository implements CategorieRepo
{
    use TryAction, FindCategorie {
        FindCategorie::findCategorie as find;
    }

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return ?Categorie[] */
    public function all(bool $isHydrate = true): ?array
    {
        $categorieList = $this->pdo
            ->query("SELECT * FROM subcategoria ORDER BY id ASC")
            ->fetchAll();

        if (count($categorieList) === 0) {
            return null;
        }

        return $isHydrate 
                ? array_map($this->hydrateCategorie(...), $categorieList)
                : $categorieList;
    }

    public function add(Categorie $categorie): int|false
    {
        $this->pdo->beginTransaction();

        $sql = "INSERT INTO subcategoria (id, nome) VALUES (NULL, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categorie->nomeCategoria);
        $status = $this->tryAction($stmt);
        $result = $status["result"];
        
        if ($result > 0) {
            $id = $status["id"];
            $categorie->setId($id);
            return $id;
        }

        return false;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();

        $sql = "DELETE FROM subcategoria WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result["result"] > 0;
    }

    /**
     * @param Categorie[] $categories
     * @return bool[]
     */
    public function removeAll(array $categories): array
    {
        $results = [];

        foreach ($categories as $categorie) {
            $results[$categorie->id] = $this->remove($categorie->id);
        }

        return $results;
    }

    public function hasProduct(int $id): bool
    {
        $hasProduct = "SELECT * FROM produto WHERE subcategoria = ?";
        $stmtHasProduct = $this->pdo->prepare($hasProduct);
        $stmtHasProduct->bindValue(1, $id, PDO::PARAM_INT);
        $stmtHasProduct->execute();
        $rowCountStmt = $stmtHasProduct->rowCount();

        return $rowCountStmt > 0;
    }

    public function update(Categorie $categorie): bool
    {
        $this->pdo->beginTransaction();

        $sql = "UPDATE subcategoria SET nome = :nome WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nome", $categorie->nomeCategoria);
        $stmt->bindValue(":id", $categorie->id, PDO::PARAM_INT);
        $status = $this->tryAction($stmt);
        $result = $status["result"];

        return $result > 0;
    }
}
