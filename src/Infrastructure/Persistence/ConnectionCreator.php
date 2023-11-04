<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Persistence;

use PDO;

final class ConnectionCreator
{
    private const SERVER = "localhost";
    private const USER = "root";
    private const PASSWORD = "";
    private static string $dataBase = "produtos like";

    public static function createConnection(bool $isTestDb = false): PDO
    {
        if ($isTestDb) self::setTestDataBase();

        $config = "mysql:host=" . self::SERVER . ";dbname=" . self::$dataBase . ";charset=utf8;";
        $connection = new PDO($config, self::USER, self::PASSWORD);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }

    private static function setTestDataBase(): void
    {
        self::$dataBase = "produtos_test";        
    }
}
