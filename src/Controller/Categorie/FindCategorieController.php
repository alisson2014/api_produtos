<?php

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class FindCategorieController implements RequestHandlerInterface
{
    use Show;
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);

        if (!$id) {
            return $this->showInvalidArgs("Id inválido");
        }

        $categorie = $this->categorieRepository->find($id);
        return $this->showResponse($categorie);
    }
}
