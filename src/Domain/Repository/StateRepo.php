<?php

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\State;

interface StateRepo
{
    public function all(bool $isHydrate = true): ?array;
    public function find(int $id, bool $isHydrate = true): null|State|array;
    public function patch(State $state): bool;
}
