<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

class NewCategorieController
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle()
    {
        $response_json = file_get_contents("php://input");
        $dados = json_decode($response_json, true);
        $nomeCategoria = $dados["nomeCategoria"];

        if (!$nomeCategoria) {
            echo json_encode([
                "status" => "Erro1"
            ]);
            exit();
        }

        $categorie = new Categorie($nomeCategoria);
        $success = $this->categorieRepository->add($categorie);

        if (!$success) {
            echo json_encode([
                "status" => "Erro"
            ]);
            exit();
        }

        echo json_encode([
            "status" => "Ok"
        ]);
    }
}
