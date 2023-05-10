<?php

//Cabeçalhos para requisição HTTP
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

include_once "../config.php";

$query_categorias = "SELECT * FROM subcategoria ORDER BY id ASC";
$result_categorias = $pdo->prepare($query_categorias);
$result_categorias->execute();

if ($result_categorias && $result_categorias->rowCount() != 0) {
    while ($row_categoria = $result_categorias->fetch(PDO::FETCH_ASSOC)) {
        extract($row_categoria);

        $listaCategorias[$id] = [
            "id" => $id,
            "nomeCategoria" => $nome
        ];

        http_response_code(200);
    }

    echo json_encode($listaCategorias);
}
