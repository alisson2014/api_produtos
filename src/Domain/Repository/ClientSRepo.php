<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\ClientS;

interface ClientSRepo extends Repository
{
    public function add(ClientS $client): bool;
    public function update(ClientS $client): bool;
    public function find(int $id): ClientS|array;
}
