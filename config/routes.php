<?php

declare(strict_types=1);

return [
    "/categorias" => [
        "GET" => Produtos\Action\Controller\Categorie\CategorieGetController::class,
        "POST" => Produtos\Action\Controller\Categorie\CategoriePostController::class,
        "PUT" => Produtos\Action\Controller\Categorie\CategoriePutController::class,
        "DELETE" => Produtos\Action\Controller\Categorie\CategorieDeleteController::class,
        "OPTIONS" => Produtos\Action\Controller\Categorie\CategorieOptionsController::class
    ],
    "/produtos" => [
        "GET" => Produtos\Action\Controller\Product\ProductGetController::class,
        "POST" => Produtos\Action\Controller\Product\ProductPostController::class,
        "PUT" => Produtos\Action\Controller\Product\ProductPutController::class,
        "DELETE" => Produtos\Action\Controller\Product\ProductDeleteController::class,
        "OPTIONS" => Produtos\Action\Controller\Product\ProductOptionsController::class
    ],
    "/orcamentos" => [
        "GET" => Produtos\Action\Controller\Client\ClientGetController::class,
        "POST" => Produtos\Action\Controller\Client\ClientPostController::class,
        "PUT" => Produtos\Action\Controller\Client\ClientPutController::class,
        "DELETE" => Produtos\Action\Controller\Client\ClientDeleteController::class
    ],
    "/orcProd" => [
        "GET" => Produtos\Action\Controller\Budget\BudgetGetController::class
    ]
];
