<?php
$server = "localhost";
$user = "root";
$password = "";
$dbName = "produtos like";

try {
    $pdo = new PDO("mysql:host={$server};dbname={$dbName};charset=utf8;", $user, $password);
} catch (Exception $erro) {
    echo "
            <p>Erro ao conectar com o a base de dados.</p>
            <p style='color: #f00'>{$erro}</p>
        ";
}
