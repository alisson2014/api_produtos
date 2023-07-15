<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Budget
{
    public int $id;

    public function __construct(
        public Client $client,
        public Product $product,
        public float $total
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
