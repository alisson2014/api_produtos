<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

trait AccessProperties
{
    public function __get(string $method): mixed
    {   
        $method = "get" . ucfirst($method);
        return $this->$method();
    }
}
