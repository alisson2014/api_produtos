<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategoryRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategoriePutController implements RequestHandlerInterface
{
    public function __construct(
        private CategoryRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);

        try {
            $id = Helper::validaId($body->id);
            $categorieName = Helper::notNull($body->nomeCategoria, "Nome da categoria");
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        $categorie = new Categorie($categorieName);
        $categorie->setId($id);

        if (!$this->categorieRepository->update($categorie)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Categoria editada com sucesso");
    }
}
