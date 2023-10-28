<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Budget extends Model
{
    public function __construct(
        private Client $client,
        private Product $product,
        private float $total
    ) {
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
