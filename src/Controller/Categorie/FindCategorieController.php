<?php

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class FindCategorieController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);

        if (!$id) {
            return new Response(304, body: json_encode([
                "status" => "Id inválido"
            ]));
        }

        $categorie = $this->categorieRepository->find($id);

        return new Response(200, ["Content-Type" => "application-json"], json_encode($categorie));
    }
}
