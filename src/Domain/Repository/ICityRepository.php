<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\City;

interface ICityRepository extends IRepository 
{
    public function find(int $id, bool $isHydrate = true): null|City|array;
}
