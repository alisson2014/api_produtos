<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Persistence;

use PDO;

final class ConnectionCreator
{
    private const SERVER = "localhost";
    private const USER = "root";
    private const PASSWORD = "";
    private const DATA_BASE = "produtos like";

    /** @return PDO */
    public static function createConnection(): PDO
    {
        $config = "mysql:host=" . self::SERVER . ";dbname=" . self::DATA_BASE . ";charset=utf8;";
        $connection = new PDO($config, self::USER, self::PASSWORD);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
