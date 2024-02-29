<?php

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\City;

interface ICityRepository extends Repository 
{
    public function find(int $id, bool $isHydrate = true): null|City|array;
}
