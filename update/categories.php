<?php

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

//Cabeçalhos HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config.php";
require_once "../autoload.php";

// $response_json = file_get_contents("php://input");
// $dados = json_decode($response_json, true);

$response = [
    "status" => false,
    "message" => "Erro ao editar no banco de dados."
];

$categorie = new Categorie("cachorro");
$categorie->setId(273);
$conn = ConnectionCreator::createConnection();
$repo = new CategorieRepository($conn);
$status = $repo->update($categorie);

if ($status) {
    echo json_encode([
        "status" => true,
        "message" => "Categoria editada com sucesso."
    ]);
} else {
    echo json_encode($response);
}

// if ($dados) {
//     $queryCategorie =  "UPDATE subcategoria SET nome = :nome WHERE id = :id LIMIT 1";
//     $editCategorie = $pdo->prepare($queryCategorie);
//     $editCategorie->bindParam(":id", $dados["id"], PDO::PARAM_INT);
//     $editCategorie->bindParam(":nome", $dados["nome"], PDO::PARAM_STR);
//     $editCategorie->execute();

//     if ($editCategorie->rowCount()) {
//         $response = [
//             "status" => true,
//             "message" => "Categoria editada com sucesso."
//         ];
//         http_response_code(200);
//         echo json_encode($response);
//     } else echo json_encode($response);
// } else echo json_encode($response);
