<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Categorie;

interface CategorieRepo extends Repository
{
    public function add(Categorie $categorie): int|false;
    public function update(Categorie $categorie): int|false;
    public function find(int $id, bool $isHydrate = true): null|Categorie|array;
}
