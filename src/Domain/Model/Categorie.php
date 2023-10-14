<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;
use Produtos\Action\Service\AccessProperties;
use Produtos\Action\Service\SetProperties;

readonly class Categorie
{
    use AccessProperties;
    private int $id;

    public function __construct(
        private string $nomeCategoria
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNomeCategoria(): string 
    {
        return $this->nomeCategoria;
    }
}
