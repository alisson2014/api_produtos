<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Budget;

interface BudgetRepo extends Repository
{
    public function add(Budget $budget): bool;
    public function update(Budget $budget): bool;
    public function find(int $id, bool $isHydrate = true): ?Budget;
}
