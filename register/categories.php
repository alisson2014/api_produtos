<?php

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";

$response_json = file_get_contents("php://input");
$data = json_decode($response_json, true);

$response = [
    "status" => false,
    "message" => "Categoria não cadastrada com sucesso!"
];

if ($data) {
    $queryCategoria = "INSERT INTO subcategoria VALUES (NULL, :nome)";
    $registerCategoria = $pdo->prepare($queryCategoria);

    $registerCategoria->bindParam(":nome", $data["nome"], PDO::PARAM_STR);
    $registerCategoria->execute();

    if ($registerCategoria->rowCount()) {
        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Categoria cadastrada com sucesso"
        ]);
    } else {
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => false,
        "message" => "Categoria não cadastrada com sucesso!"
    ];
    echo json_encode($response);
}
