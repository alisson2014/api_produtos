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
        private Categorie $categoria
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

    public function getCategoria(): Categorie
    {
        return $this->categoria;        
    }

    public function getIdCategoria(): int
    {
        return $this->categoria->getId();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
