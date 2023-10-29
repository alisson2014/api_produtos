<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Client;
use Produtos\Action\Domain\Repository\Repository;


interface ClientRepo extends Repository
{
    public function add(Client $client): bool;
    public function update(Client $client): bool;
    public function find(int $id, bool $isHydrate = true): ?Client;
}
