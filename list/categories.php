<?php

use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Lister;

require_once "../config.php";
require_once "../autoload.php";

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

// $sqlQuery = "SELECT * FROM subcategoria ORDER BY id ASC";
// $list = new Lister($sqlQuery, $pdo);
// $consult = $list->sqlConsult();
// $http_response_code = 502;

// if ($list->hasData()) {
//     $http_response_code = 200;
//     $data = $list->returnsData();
//     echo json_encode($data);
// }

$conn = ConnectionCreator::createConnection();
$repository = new CategorieRepository($conn);
$list = $repository->all();
echo json_encode($list);

http_response_code(200);
