<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

trait TryAction
{
    private function tryAction(\PDOStatement|bool $stmt): array 
    {
        $result = 0;

        try {
            $stmt->execute();
            $result = $stmt->rowCount();
            $lastId = $this->pdo->lastInsertId();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        return [
            "result" => $result ?? "",
            "id" => intval($lastId)
        ];
    }
}
