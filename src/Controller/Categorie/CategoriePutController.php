<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategoriePutController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);
        $id = Helper::filterInt($body->id);

        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        try {
            $nomeCategoria = Helper::notNull($body->nomeCategoria);
        } catch (\InvalidArgumentException) {
            return Helper::invalidRequest("Nome da categoria não pode ser vázio.");
        }

        $categorie = new Categorie($nomeCategoria);
        $categorie->setId($id);

        if (!$this->categorieRepository->update($categorie)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Categoria editada com sucesso");
    }
}
