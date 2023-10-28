<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Categorie extends Model
{
    public function __construct(
        private string $nomeCategoria
    ) {
    }

    public function getNomeCategoria(): string 
    {
        return $this->nomeCategoria;
    }
}
