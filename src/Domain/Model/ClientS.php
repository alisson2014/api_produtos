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
        private \DateTime $dataNascimento,
        private int $numeroTelefone,
        private ?string $email = null
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }

    public function getDataNascimento(): \DateTime
    {
        return $this->dataNascimento;
    }

    public function getNumeroTelefone(): int
    {
        return $this->numeroTelefone;
    }

    public function getEmail(): ?string
    {
        return $this->email;        
    }
}
