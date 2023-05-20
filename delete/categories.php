<?php

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$response = [
    "status" => true,
    "message" => "Categoria apagada com sucesso! $id"
];

$queryCategorie = "DELETE FROM subcategoria WHERE id = :id LIMIT 1";
$deleteCategorie = $pdo->prepare($queryCategorie);
$deleteCategorie->bindParam(":id", $id, PDO::PARAM_INT);

if ($deleteCategorie->execute()) {
    http_response_code(200);
    echo json_encode($response);
} else {
    $response = [
        "status" => false,
        "message" => "Erro ao excluir categoria"
    ];
    http_response_code(400);
    echo json_encode($response);
}
