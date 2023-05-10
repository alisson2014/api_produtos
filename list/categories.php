<?php

use Produtos\Action\Service\Lister;

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";
require_once "../autoload.php";

$query = new Lister("SELECT * FROM subcategoria ORDER BY id ASC", $pdo);
$consult = $query->sqlConsult();

if ($query->hasData()) {
    $data = $query->returnsData();
    echo json_encode($data);
}

http_response_code(200);
