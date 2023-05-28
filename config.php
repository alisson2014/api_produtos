<?php
const SERVER = "localhost";
const USER  = "root";
const PASSWORD = "";
const DBNAME = "produtos like";

try {
    $pdo = new PDO("mysql:host=" . SERVER . ";dbname=" . DBNAME . ";charset=utf8;", USER, PASSWORD);
} catch (Exception $erro) {
    echo "<p>Erro ao conectar com o a base de dados.</p>";
}
