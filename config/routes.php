<?php

declare(strict_types=1);

return [
    "/categorias" => [
        "GET" => Produtos\Action\Controller\Categorie\CategorieGetController::class,
        "POST" => Produtos\Action\Controller\Categorie\CategoriePostController::class,
        "PUT" => Produtos\Action\Controller\Categorie\CategoriePutController::class,
        "DELETE" => Produtos\Action\Controller\Categorie\CategorieDeleteController::class
    ],
];
