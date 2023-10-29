<?php

declare(strict_types=1);

namespace Produtos\Action\Domain\Model;

readonly abstract class Model 
{
    protected int $id;
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function __get(string $method): mixed
    {   
        $method = "get" . ucfirst($method);
        return $this->$method();
    }
}
