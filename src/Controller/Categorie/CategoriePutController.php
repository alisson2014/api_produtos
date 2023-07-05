<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategoriePutController implements RequestHandlerInterface
{
    use Show;
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());
        $id = filter_var($body->id, FILTER_VALIDATE_INT);
        $nomeCategoria = $body->nomeCategoria;

        if (!$id) {
            return $this->showInvalidArgs("Id inválido.");
        }

        if (empty($nomeCategoria)) {
            return $this->showInvalidArgs("Nome da categoria não pode ser vázio.");
        }

        $categorie = new Categorie($nomeCategoria);
        $categorie->setId($id);
        $success = $this->categorieRepository->update($categorie);

        if (!$success) {
            return $this->showInternalError();
        }

        return $this->showStatus("Categoria editada com sucesso");
    }
}
