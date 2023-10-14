<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;
use Produtos\Action\Service\AccessProperties;

readonly class Product
{
    use AccessProperties;
    private int $id;

    public function __construct(
        private string $nomeProduto,
        private float $valor,
        private ?int $idCategoria = null
    ) {
    }

    public function getNomeProduto(): string
    {
        return $this->nomeProduto;        
    }

    public function getValor(): float
    {
        return $this->valor;        
    }

    public function getIdCategoria(): int|null
    {
        return $this->idCategoria;        
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
