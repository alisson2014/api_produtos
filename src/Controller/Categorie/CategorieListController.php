<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class CategorieListController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $categorieList = array_map(function (Categorie $categorie): array {
            return [
                "id" => $categorie->id,
                "nomeCategoria" => $categorie->categorieName
            ];
        }, $this->categorieRepository->all());

        return new Response(200, ["Content-Type" => "application-json"], json_encode($categorieList));
    }
}
