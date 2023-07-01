<?php

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";
require_once "../autoload.php";

// $response_json = file_get_contents("php://input");
// $data = json_decode($response_json, true);

$response = [
    "status" => false,
    "message" => "Categoria não cadastrada com sucesso."
];

$conn = ConnectionCreator::createConnection();
$repo = new CategorieRepository($conn);
$categorie = new Categorie("Novo aqui");
$insert = $repo->add($categorie);

if ($insert) {
    $response = [
        "status" => true,
        "message" => "Categoria cadastrada com sucesso."
    ];
    echo json_encode($response);
} else {
    echo json_encode($response);
}

// if ($data) {
//     $queryCategoria = "INSERT INTO subcategoria VALUES (NULL, :nome)";
//     $registerCategoria = $pdo->prepare($queryCategoria);

//     $registerCategoria->bindParam(":nome", $data["nome"], PDO::PARAM_STR);
//     $registerCategoria->execute();

//     if ($registerCategoria->rowCount()) {
//         $response = [
//             "status" => true,
//             "message" => "Categoria cadastrada com sucesso."
//         ];
//         http_response_code(200);
//         echo json_encode($response);
//     } else echo json_encode($response);
// } else echo json_encode($response);
