<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategorieRepository;

class CategorieListController
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle()
    {
        $list = $this->categorieRepository->all();
        echo json_encode($list);
        http_response_code(200);
    }
}
