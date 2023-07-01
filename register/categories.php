<?php

use Produtos\Action\Controller\Categorie\NewCategorieController;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";
require_once "../autoload.php";

$repo = new CategorieRepository($conn);
$controller = new NewCategorieController($repo);
$controller->handle();
