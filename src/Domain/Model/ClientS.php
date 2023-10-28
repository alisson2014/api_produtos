<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use Produtos\Action\Service\AccessProperties;

readonly class ClientS
{
    use AccessProperties;
    private int $id;

    public function __construct(
        private string $nomeCliente,
        string $cpf,
        private Adress $endereco 
    ) {
        if ($this->validateCpf($cpf)) {
            $this->cpf = $cpf;
        }
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }

    public function getCpf(): string
    {
        return $this->cpf;        
    }

    private function validateCpf(string $cpf): bool
    {
        $cpf = filter_var($cpf, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => '/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/'
            ]
        ]);

        if (!$cpf) {
            throw new \InvalidArgumentException("Erro, cpf inválido!");
        }

        return true;
    }
}
