<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieListController implements RequestHandlerInterface
{
    use Show;
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listCategories();
        }

        $filterId = filter_var($id, FILTER_VALIDATE_INT);
        return $this->findCategorie($filterId);
    }

    private function listCategories(): Response
    {
        $categorieList = array_map(function (Categorie $categorie): array {
            return [
                "id" => $categorie->id,
                "nomeCategoria" => $categorie->categorieName
            ];
        }, $this->categorieRepository->all());

        return $this->showResponse($categorieList);
    }

    /** @param int|bool $id */
    private function findCategorie(int|bool $id): Response
    {
        if (!$id) {
            return $this->showInvalidArgs("Id inválido");
        }

        $categorie = $this->categorieRepository->find($id);
        return $this->showResponse($categorie);
    }
}
