<?php

declare(strict_types=1);

use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;

$conn = ConnectionCreator::createConnection();
