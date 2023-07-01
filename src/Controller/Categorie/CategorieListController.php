<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

class CategorieListController
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle()
    {
        $categorieList = array_map(function (Categorie $categorie): array {
            return [
                "id" => $categorie->id,
                "nomeCategoria" => $categorie->categorieName
            ];
        }, $this->categorieRepository->all());

        http_response_code(200);
        header("Content-Type: application-json");
        echo json_encode($categorieList);
    }
}
