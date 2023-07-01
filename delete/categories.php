<?php

use Produtos\Action\Controller\Categorie\DeleteCategorieController;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";
require_once "../autoload.php";

$repository = new CategorieRepository($conn);
$controller = new DeleteCategorieController($repository);
$controller->handle();
