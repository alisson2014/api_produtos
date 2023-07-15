<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Budget
{
    public int $id;

    public function __construct(
        private Client $client,
        private Product $product,
        private float $total
    ) {
    }
}
