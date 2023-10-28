<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Adress extends Model
{
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
