<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class City extends Model
{
    public function __construct(
        private string $nome,
        private State $estado
    ){
    }

    public function getNome(): string
    {
        return $this->nome;        
    }

    public function getEstado(): State
    {
        return $this->estado;
    }
}
