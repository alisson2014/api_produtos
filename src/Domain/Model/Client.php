<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use DateTime;

readonly class Client extends Model
{
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
}
