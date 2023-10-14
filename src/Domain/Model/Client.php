<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use DateTime;
use Produtos\Action\Service\AccessProperties;

readonly class Client
{
    use AccessProperties;
    private int $id;

    public function __construct(
        private string $nomeCliente,
        private DateTime $dataOrcamento
    ) {
    }

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }

    public function getDataOrcamento(): DateTime
    {
        return $this->dataOrcamento;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
