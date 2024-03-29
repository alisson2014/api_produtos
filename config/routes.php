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
    "/estados" => [
        "GET" => Produtos\Action\Controller\State\StateGetController::class,
        "PATCH" => Produtos\Action\Controller\State\StatePatchController::class
    ],
    "/enderecos" => [
        "GET" => Produtos\Action\Controller\Address\AddressGetController::class,
        "POST" => Produtos\Action\Controller\Address\AddressPostController::class,
        "PUT" => Produtos\Action\Controller\Address\AddressPutController::class,
        "DELETE" => Produtos\Action\Controller\Address\AddressDeleteController::class,
        "OPTIONS" => Produtos\Action\Controller\Address\AddressOptionsController::class
    ]
];
