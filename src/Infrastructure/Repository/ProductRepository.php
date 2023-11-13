<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Domain\Repository\ProductRepo;
use Produtos\Action\Service\FindCategorie;
use Produtos\Action\Service\TryAction;

final class ProductRepository implements ProductRepo
{
    use TryAction, FindCategorie;

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return ?Product[] */
    public function all(bool $isHydrate = true): ?array
    {
        $productList = $this->pdo
            ->query(
                "SELECT p.*,s.nome AS nomeCategoria 
                FROM produto AS p 
                JOIN subcategoria AS s ON s.id = p.subcategoria"
            )
            ->fetchAll();

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
        $sql = "INSERT INTO produto (id, nome, valor, subcategoria)
                VALUES (NULL, :produto, :valor, :categoria_id)";
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
                subcategoria = :categoria_id, 
                valor = :valor
                WHERE id = :id LIMIT 1";
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
        $stmt = $this->pdo->prepare("SELECT * FROM produto WHERE id = ?;");
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
            $this->findCategorie($productData["subcategoria"])
        );
        $product->setId($productData["id"]);

        return $product;
    }
}
