<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Repository;

use PDO;
use Produtos\Action\Domain\Model\{Budget, Client, Product};
use Produtos\Action\Domain\Repository\BudgetRepo;
use Produtos\Action\Service\TryAction;

final class BudgetRepository implements BudgetRepo
{
    use TryAction;
    public function __construct(
        private PDO $pdo
    ) {
    }

    /** @return Budget[] */
    public function all(): array
    {
        $budgetsList = $this->pdo
            ->query("SELECT o.id AS idOrcamento, o.nomeCliente  AS nomeCliente, o.data AS data, p.id AS idProduto, p.nome AS nomeProduto, p.valor AS valorProduto, SUM(p.valor * po.quantidade) AS total FROM orcamento AS o JOIN produtosorcamento AS po ON po.orcamento = o.id JOIN produto AS p ON p.id = po.produto GROUP BY o.id")
            ->fetchAll();

        if (count($budgetsList) === 0) {
            return [];
        }

        return array_map(
            $this->hydrateBudget(...),
            $budgetsList
        );
    }

    public function find(int $id): Budget|array
    {
        $stmt = $this->pdo->prepare(
            "SELECT o.id AS idOrcamento, 
                o.nomeCliente  AS nomeCliente, 
                o.data AS data, 
                p.id AS idProduto, 
                p.nome AS nomeProduto, 
                p.valor AS valorProduto, 
                SUM(p.valor * po.quantidade) AS total 
                FROM orcamento AS o 
                JOIN produtosorcamento AS po ON po.orcamento = o.id 
                JOIN produto AS p ON p.id = po.produto 
                WHERE o.id = ?
                GROUP BY o.id 
            "
        );
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return [];
        }

        return $this->hydrateBudget($result);
    }

    public function add(Budget $budget): bool
    {
        $this->pdo->beginTransaction();
        $sql = "INSERT INTO subcategoria (id, nome) VALUES (NULL, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $budget->id, PDO::PARAM_STR);
        $status = $this->tryAction($stmt, true);
        $result = $status["result"];

        if ($result) {
            $budget->setId($status["id"]);
            return true;
        }

        return $result > 0;
    }

    public function remove(int $id): bool
    {
        $this->pdo->beginTransaction();

        $sql = "DELETE FROM subcategoria WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    public function update(Budget $budget): bool
    {
        $this->pdo->beginTransaction();
        $sql = "UPDATE subcategoria SET nome = :nome WHERE id = :id LIMIT 1;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nome", $budget->client, PDO::PARAM_STR);
        $stmt->bindValue(":id", $budget->id, PDO::PARAM_INT);
        $result = $this->tryAction($stmt);

        return $result > 0;
    }

    private function hydrateBudget(array $budgetData): Budget
    {
        $date = new \DateTime($budgetData["data"]);
        $client = new Client($budgetData["nomeCliente"], $date);
        $product = new Product($budgetData["nomeProduto"], floatval($budgetData["valorProduto"]));
        $budget = new Budget($client, $product, floatval($budgetData["total"]));
        $budget->setId($budgetData["idOrcamento"]);
        return $budget;
    }
}
