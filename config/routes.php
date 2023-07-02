<?php

declare(strict_types=1);

return [
    "GET|/listar-categorias" => Produtos\Action\Controller\Categorie\CategorieListController::class,
    "GET|/encontrar-categoria" => \Produtos\Action\Controller\Categorie\FindCategorieController::class,
    "POST|/criar-categoria" => Produtos\Action\Controller\Categorie\NewCategorieController::class,
    "PUT|/editar-categoria" => Produtos\Action\Controller\Categorie\EditCategorieController::class,
    "DELETE|/remover-categoria" => Produtos\Action\Controller\Categorie\DeleteCategorieController::class
];
