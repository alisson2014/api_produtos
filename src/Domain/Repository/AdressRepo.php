<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Adress;

interface AdressRepo extends Repository
{
    public function add(Adress $adress): bool;
    public function update(Adress $adress): bool;
    public function find(int $id): Adress|array;
}
