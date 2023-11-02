<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategoriePostController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);

        try {
            $nomeCategoria = Helper::notNull($body->nomeCategoria);
        } catch (\InvalidArgumentException) {
            return Helper::invalidRequest("Nome da categoria não pode ser vazio");
        }

        $categorie = new Categorie($nomeCategoria);

        if (!$this->categorieRepository->add($categorie)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Categoria cadastrada com sucesso", 201);
    }
}
