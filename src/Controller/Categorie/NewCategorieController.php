<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class NewCategorieController implements RequestHandlerInterface
{
    use Show;
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dados = json_decode($request->getBody()->getContents());
        $nomeCategoria = $dados->nomeCategoria;

        if (empty($nomeCategoria)) {
            return $this->showInvalidArgs("Nome da categoria não pode ser vázio");
        }

        $categorie = new Categorie($nomeCategoria);
        $success = $this->categorieRepository->add($categorie);

        if (!$success) {
            return $this->showInternalError();
        }

        return $this->showStatusOk("Categoria cadastrada com sucesso", 201);
    }
}
