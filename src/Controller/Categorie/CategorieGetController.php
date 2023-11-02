<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieGetController implements RequestHandlerInterface
{
    private int $id;
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

        try {
            $this->id = Helper::validaId($id);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        return $this->findCategorie();
    }

    private function listCategories(): Response
    {
        $allCategories = $this->categorieRepository->all(false);

        return empty($allCategories) 
                ? Helper::nothingFound() 
                : Helper::showResponse($allCategories);
    }

    private function findCategorie(): Response
    {
        $categorie = $this->categorieRepository->find($this->id, false);

        return empty($categorie) 
                ? Helper::nothingFound() 
                : Helper::showResponse($categorie);
    }
}
