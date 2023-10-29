<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieGetController implements RequestHandlerInterface
{
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
            return Helper::invalidRequest("Id inválido");
        }

        return $this->findCategorie($id);
    }

    /** @return ResponseInterface */
    private function listCategories(): ResponseInterface
    {
        $allCategories = $this->categorieRepository->all(false);

        if (empty($allCategories)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($allCategories);
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    private function findCategorie(int $id): ResponseInterface
    {
        $categorie = $this->categorieRepository->find($id, false);

        if (empty($categorie)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($categorie);
    }
}
