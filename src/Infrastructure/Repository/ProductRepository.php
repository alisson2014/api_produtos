<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Domain\Repository\ProductRepo;
use Produtos\Action\Service\TryAction;

final class ProductRepository implements ProductRepo
{
    use TryAction;

    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return Product[] */
    public function all(): array
    {
        $categorieList = $this->pdo
            ->query(
                "SELECT p.*,s.nome as nomeCategoria 
                FROM produto as p 
                JOIN subcategoria as s 
                ON s.id = p.subcategoria"
            )
            ->fetchAll();

        return array_map(
            $this->hydrateProduct(...),
            $categorieList
        );
    }

    public function add(Product $product): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO produto (id, nome, valor, subcategoria)
        VALUES (NULL, :produto, :valor, :categoria_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":produto", $product->nomeProduto);
        $stmt->bindValue(":valor", $product->valor);
        $stmt->bindValue(":categoria_id", $product->idCategoria);
        $status = $this->tryAction($stmt, true);
        $result = $status["result"];

        if ($result) {
            $product->setId($status["id"]);
        }

        return $result;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();
        $sql = "DELETE FROM produto WHERE id = ?;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result;
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
        $stmt->bindValue(":valor", $product->valor);
        $status = $this->tryAction($stmt);

        return $status;
    }

    public function find(int $id): Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produto WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->hydrateProduct($stmt->fetch());
    }

    private function hydrateProduct(array $productData): Product
    {
        $categoria = $this->findCategorie($productData["subcategoria"]);
        $product = new Product($productData["nome"], $categoria, $productData["valor"], $productData["subcategoria"]);
        $product->setId($productData["id"]);

        return $product;
    }

    private function findCategorie(int $id): string
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subcategoria WHERE id = ?;");
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch = $stmt->fetch();

        return $fetch["nome"];
    }
}
