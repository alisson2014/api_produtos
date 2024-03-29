<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

interface IRepository
{
    public function all(bool $isHydrate = true): ?array;
    public function remove(int $id): bool;
}