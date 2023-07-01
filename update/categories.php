<?php

use Produtos\Action\Controller\Categorie\EditCategorieController;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config.php";
require_once "../autoload.php";

$repo = new CategorieRepository($conn);
$controller = new EditCategorieController($repo);
$controller->handle();
