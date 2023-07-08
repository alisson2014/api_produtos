<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Categorie
{
    public int $id;

    public function __construct(
        public string $nomeCategoria
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
