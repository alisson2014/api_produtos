<?php

declare(strict_types=1);

use Produtos\Action\Controller\Categorie\CategorieListController;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../config.php");

$routes = require_once(__DIR__ . "/../config/routes.php");

$pathInfo = $_SERVER["PATH_INFO"] ?? "/";
$httpMethod = $_SERVER["REQUEST_METHOD"];

$key = "$httpMethod|$pathInfo";
$categorieRepo = new CategorieRepository($conn);

if (array_key_exists($key, $routes)) {
    $controllerClass = $routes["$httpMethod|$pathInfo"];
    $controller = new $controllerClass($categorieRepo);
} else {
    $controller = new CategorieListController($categorieRepo);
}

$controller->handle();
