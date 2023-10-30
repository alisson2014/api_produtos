<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Address;

interface AddressRepo extends Repository
{
    public function add(Address $adress): bool;
    public function update(Address $adress): bool;
    public function find(int $id, bool $isHydrate = true): null|Address|array;
    public function findByCep(int $cep): ?array;
}
