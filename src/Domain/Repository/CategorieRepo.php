<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Repository;

use Produtos\Action\Domain\Model\Categorie;

interface CategorieRepo extends Repository
{
    public function add(Categorie $categorie): bool;
    public function update(Categorie $categorie): bool;
    public function find(int $id): ?Categorie;
}
