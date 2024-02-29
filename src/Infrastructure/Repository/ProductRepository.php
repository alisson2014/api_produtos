<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Domain\Repository\IProductRepository;
use Produtos\Action\Service\{FindCategorie, TryAction};

final class ProductRepository implements IProductRepository
{
    use TryAction, FindCategorie;

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return ?Product[] */
    public function all(bool $isHydrate = true): ?array
    {
        $sql = "SELECT 
                    p.*, 
                    c.nome AS nomeCategoria 
                FROM produto AS p 
                    INNER JOIN categoria c ON c.id = p.categoria_id
                ORDER BY p.id DESC";
        $productList = $this->pdo->query($sql)->fetchAll();

        if (count($productList) === 0) {
            return null;
        }

        return $isHydrate 
                ? array_map($this->hydrateProduct(...), $productList)
                : $productList;
    }

    public function add(Product $product): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO produto (nome, valor, categoria_id)
                VALUES (:produto, :valor, :categoria_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":produto", $product->nomeProduto);
        $stmt->bindValue(":valor", strval($product->valor));
        $stmt->bindValue(":categoria_id", $product->idCategoria, PDO::PARAM_INT);
        $status = $this->tryAction($stmt);
        $result = $status["result"];

        if ($result > 0) {
            $product->setId($status["id"]);
            return true;
        }

        return false;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM produto WHERE id = ?;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function update(Product $product): bool
    {
        $this->pdo->beginTransaction();

        $sql = "UPDATE produto 
                SET nome = :produto, 
                categoria_id = :categoria_id, 
                valor = :valor
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $product->id, PDO::PARAM_INT);
        $stmt->bindValue(":produto", $product->nomeProduto);
        $stmt->bindValue(":categoria_id", $product->idCategoria, PDO::PARAM_INT);
        $stmt->bindValue(":valor", strval($product->valor));
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function find(int $id, bool $isHydrate = true): null|Product|array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, valor, categoria_id FROM produto WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return $isHydrate ? $this->hydrateProduct($result) : $result;
    }

    private function hydrateProduct(array $productData): Product
    {
        $product = new Product(
            $productData["nome"], 
            floatval($productData["valor"]), 
            $this->findCategorie($productData["categoria_id"])
        );
        $product->setId($productData["id"]);

        return $product;
    }
}
