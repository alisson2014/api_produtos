<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieDeleteController implements RequestHandlerInterface
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
            return Helper::invalidRequest("Id inválido");
        }

        $hasProduct = $this->categorieRepository->hasProduct($id);

        if ($hasProduct) {
            return Helper::invalidRequest("Esta categoria não pode ser excluida pois possui produtos vinculados a ela.");
        }

        $result = $this->categorieRepository->remove($id);

        if (!$result) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
