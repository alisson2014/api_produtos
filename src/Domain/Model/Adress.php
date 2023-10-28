<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use Produtos\Action\Service\AccessProperties;

readonly class Adress
{
    use AccessProperties;
    private int $id;

    public function __construct(
        private string $cidade,
        private string $bairro,
        private string $rua,
        private string $numero
    ) {
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function getRua(): string
    {
        return $this->rua;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setId(int $id): void
    {
        $this->id = $id;        
    }

    public function setCidade(string $novaCidade): void
    {
        $this->cidade = $novaCidade;
    }

    public function setBairro(string $novoBairro): void
    {
        $this->bairro = $novoBairro;
    }

    public function setRua(string $novaRua): void
    {
        $this->rua = $novaRua;
    }

    public function setNumero(string $novoNumero): void
    {
        $this->numero = $novoNumero;
    }
}
