<?php

declare(strict_types=1);

return [
    "GET|/categorias" => Produtos\Action\Controller\Categorie\CategorieListController::class,
    "POST|/criar-categoria" => Produtos\Action\Controller\Categorie\NewCategorieController::class,
    "PUT|/editar-categoria" => Produtos\Action\Controller\Categorie\EditCategorieController::class,
    "DELETE|/remover-categoria" => Produtos\Action\Controller\Categorie\DeleteCategorieController::class
];
