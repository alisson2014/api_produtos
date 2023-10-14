<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;
use Produtos\Action\Service\AccessProperties;

readonly class Budget
{
    use AccessProperties;
    private int $id;

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

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
