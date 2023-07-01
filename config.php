<?php

declare(strict_types=1);

use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;

require_once "autoload.php";

$conn = ConnectionCreator::createConnection();
