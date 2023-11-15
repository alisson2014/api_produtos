<?php

declare(strict_types=1);

namespace Produtos\Action\Infrastructure\Persistence;

use PDO;
use PhpParser\Node\Stmt\Return_;

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

    public static function createMemoryConn(string $table = "Subcategoria"): PDO
    {
        $createTable = "createTable" . ucfirst($table);
        $pdo = new PDO("sqlite::memory:");
        $pdo->exec(self::$createTable());
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

    private static function createTableSubcategoria(): string
    {
        return "CREATE TABLE IF NOT EXISTS subcategoria (
            id INTEGER PRIMARY KEY, 
            nome TEXT
        )";        
    }

    private static function createTableProduto(): string
    {
        return "CREATE TABLE IF NOT EXISTS produto (
            id INTEGER PRIMARY KEY,
            nome TEXT,
            valor TEXT,
            subcategoria INTEGER 
        )";
    }

    private static function setTestDataBase(): void
    {
        self::$dataBase = "produtos_test";        
    }
}
