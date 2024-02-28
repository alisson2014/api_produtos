<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class State extends Model
{
    public function __construct(
        private string $siglaUf
    ){   
    }

    public function getSiglaUf(): string 
    {
        return $this->siglaUf;    
    }
}
