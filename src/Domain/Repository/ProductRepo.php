<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Product;

interface ProductRepo extends Repository
{
    public function add(Product $product): bool;
    public function update(Product $product): bool;
    public function find(int $id, bool $isHydrate = true): null|Product|array;
}
