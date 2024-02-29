<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Infrastructure\Repository\CategoryRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieDeleteController implements RequestHandlerInterface
{
    public function __construct(
        private CategoryRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        
        try {
            $id = Helper::validaId($queryParams["id"]);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        if ($this->categorieRepository->hasProduct($id)) {
            $msg = "Esta categoria não pode ser excluida pois possui produtos vinculados a ela.";
            return Helper::showStatus($msg, 409, "error");
        }

        if (!$this->categorieRepository->remove($id)) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
