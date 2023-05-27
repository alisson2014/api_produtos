<?php

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: UPDATE");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";

$response_json = file_get_contents("php://input");
$data = json_decode($response_json, true);

$response = [
    "status" => false,
    "message" => "Produto não editado com sucesso."
];

if ($data) {
    $queryProdutos = "UPDATE produto SET nome = :produto, subcategoria = :categorias_id, valor = :valor
    WHERE id = :id LIMIT 1";
    $registerProduct = $pdo->prepare($queryProdutos);

    $registerProduct->bindParam(":id", $data["id"], PDO::PARAM_INT);
    $registerProduct->bindParam(":produto", $data["nome"], PDO::PARAM_STR);
    $registerProduct->bindParam(":valor", $data["valor"], PDO::PARAM_STR);
    $registerProduct->bindParam(":categorias_id", $data["idCategoria"], PDO::PARAM_INT);

    $registerProduct->execute();

    if ($registerProduct->rowCount()) {
        $response = [
            "status" => true,
            "message" => "Produto editado com sucesso."
        ];
        http_response_code(200);
        echo json_encode($response);
    } else echo json_encode($response);
} else echo json_encode($response);
