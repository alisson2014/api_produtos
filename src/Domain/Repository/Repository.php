<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

interface Repository
{
    public function all(): ?array;
    public function remove(int $id): bool;
}