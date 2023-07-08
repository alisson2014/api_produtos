<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieGetController implements RequestHandlerInterface
{
    use Show;

    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listCategories();
        }

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return $this->showInvalidArgs("Id inválido.");
        }

        return $this->findCategorie($id);
    }

    /** @return ResponseInterface */
    private function listCategories(): ResponseInterface
    {
        $categorieList = array_map(function (Categorie $categorie): array {
            return [
                "id" => $categorie->id,
                "nomeCategoria" => $categorie->nomeCategoria
            ];
        }, $this->categorieRepository->all());

        return $this->showResponse($categorieList);
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    private function findCategorie(int $id): ResponseInterface
    {
        $categorie = $this->categorieRepository->find($id);
        return $this->showResponse($categorie);
    }
}
