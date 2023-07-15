<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

use DateTime;

readonly class Client
{
    public int $id;

    public function __construct(
        public string $nomeCliente,
        public DateTime $dataOrcamento
    ) {
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
