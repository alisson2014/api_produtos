<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Product
{
    public int $id;

    public function __construct(
        public string $nomeProduto,
        public float $valor,
        public ?int $idCategoria = null
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
