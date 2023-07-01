<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategorieRepository;

class DeleteCategorieController
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle()
    {
        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

        $result = $this->categorieRepository->remove(intval($id));
        if (!$result) {
            echo json_encode([
                "status" => "Erro"
            ]);
            exit();
        }

        http_response_code(200);
        header("Content-Type: application-json");
        echo json_encode(["status" => "Ok"]);
    }
}
