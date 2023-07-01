<?php

use Produtos\Action\Controller\Categorie\CategorieListController;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

require_once "../config.php";
require_once "../autoload.php";

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

$repo = new CategorieRepository($conn);
$controller = new CategorieListController($repo);
$controller->handle();
