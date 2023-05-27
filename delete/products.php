<?php

require_once "../config.php";

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$response = [
    "status" => true,
    "message" => "Produto apagado com sucesso! $id"
];

$queryDelete = "DELETE FROM produto WHERE id = {$id} LIMIT 1";
$deleteProduct = $pdo->prepare($queryDelete);

try {
    $deleteProduct->execute();
} catch (Throwable) {
    $response = [
        "status" => false,
        "message" => "Erro ao excluir categoria"
    ];
    http_response_code(400);
}

echo json_encode($response);
