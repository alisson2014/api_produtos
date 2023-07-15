<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

trait TryAction
{
    /**
     * @param \PDOStatement|bool $stmt
     * @param bool $returnId
     * @return array|int
     */
    private function tryAction(
        \PDOStatement|bool $stmt,
        bool $returnId = false,
    ): array|int {
        try {
            $stmt->execute();

            $result = $stmt->rowCount();

            if ($returnId) {
                $lastId = $this->pdo->lastInsertId();
            }

            $this->pdo->commit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            $this->pdo->rollBack();
        }

        if ($returnId) {
            return [
                "result" => $result ?? "",
                "id" => (is_string($lastId) ? intval($lastId) : $lastId)
            ];
        }

        return $result;
    }
}
