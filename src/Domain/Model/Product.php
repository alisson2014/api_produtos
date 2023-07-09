<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Product
{
    public int $id;

    public function __construct(
        public string $nomeProduto,
        public string $categoria,
        public string $valor,
        public int $idCategoria
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setIdCategoria(int $id): void
    {
        $this->idCategoria = $id;
    }
}
