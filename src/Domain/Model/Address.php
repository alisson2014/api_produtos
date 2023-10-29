<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Address extends Model
{
    public function __construct(
        private int $cep,
        private string $uf,
        private string $cidade,
        private string $bairro,
        private string $logradouro,
        private string $numero
    ) {
    }

    public function getCep(): int
    {
        return $this->cep;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function getLogradouro(): string
    {
        return $this->logradouro;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setCep(int $novoCep): void
    {
        $this->cep = $novoCep;
    }

    public function setUf(string $novaUf): void
    {
        $this->uf = $novaUf;
    }

    public function setCidade(string $novaCidade): void
    {
        $this->cidade = $novaCidade;
    }

    public function setBairro(string $novoBairro): void
    {
        $this->bairro = $novoBairro;
    }

    public function setLogradouro(string $novoLogradouro): void
    {
        $this->logradouro = $novoLogradouro;
    }

    public function setNumero(string $novoNumero): void
    {
        $this->numero = $novoNumero;
    }
}
