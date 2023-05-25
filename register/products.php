<?php

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";

$response_json = file_get_contents("php://input");
$data = json_decode($response_json, true);

$response = [
    "status" => false,
    "message" => "Produto não cadastrada com sucesso."
];

if ($data) {
    $queryProdutos = "INSERT INTO produto VALUES
    (NULL, :produto, :valor, :categorias_id)";
    $registerProduct = $pdo->prepare($queryProdutos);

    $registerProduct->bindParam(":produto", $data["nome"], PDO::PARAM_STR);
    $registerProduct->bindParam(":valor", $data["valor"]);
    $registerProduct->bindParam(":categorias_id", $data["idCategoria"], PDO::PARAM_STR);

    $registerProduct->execute();

    if ($registerProduct->rowCount()) {
        $response = [
            "status" => true,
            "message" => "Produto cadastrada com sucesso."
        ];
        http_response_code(200);
        echo json_encode($response);
    } else echo json_encode($response);
} else echo json_encode($response);
