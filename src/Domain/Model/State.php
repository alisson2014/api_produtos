<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use InvalidArgumentException;

readonly class State extends Model
{
    private bool $active;

    public function __construct(
        private string $siglaUf,
        private string $descricao = ""
    ){   
    }

    public function getSiglaUf(): string 
    {
        return $this->siglaUf;    
    }

    public function getDescricao(): string
    {
        return $this->descricao;    
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(string $active): void
    {
        if($active !== "s" && $active !== "n") {
            throw new InvalidArgumentException("Ativo deve ser um campo do tipo 's' ou 'n'");
        }

        $this->active = $active === "s";
    }
}
