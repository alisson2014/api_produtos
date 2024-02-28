<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly class Address extends Model
{
    public function __construct(
        private string $cep,
        private City $cidade,
        private string $bairro,
        private string $logradouro,
        private string|null $numero = null
    ){
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    public function getCidade(): City
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

    public function setCep(string $novoCep): void
    {
        $this->cep = $novoCep;
    }

    public function setCidade(City $novaCidade): void
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
