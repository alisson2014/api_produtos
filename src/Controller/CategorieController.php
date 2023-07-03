<?php

declare(strict_types=1);

namespace Produtos\Action\Controller;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class CategorieController implements RequestHandlerInterface
{
    use Show;
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $body = json_decode($request?->getBody()->getContents());
        $httpMethod = $request->getMethod();
        $id = $queryParams["id"] ?? null;

        if (!is_null($id)) {
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                return $this->showInvalidArgs("Id inválido");
            }
        }

        switch ($httpMethod) {
            case "GET":
                if ($id) {
                    return $this->findCategorie($id);
                }

                return $this->listCategories();
            case "POST":
                return $this->postCategorie($body);
            case "PUT":
                return $this->putCategorie($body);
            case "DELETE":
                return $this->deleteCategorie($id);
            default:
                return $this->showInternalError();
        }
    }

    /** @return Response */
    private function listCategories(): Response
    {
        $categorieList = array_map(function (Categorie $categorie): array {
            return [
                "id" => $categorie->id,
                "nomeCategoria" => $categorie->categorieName
            ];
        }, $this->categorieRepository->all());

        return $this->showResponse($categorieList);
    }

    /**
     * @param int $id
     * @return Response
     */
    private function findCategorie(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        return $this->showResponse($categorie);
    }

    /**
     * @param object $body
     * @return Response
     */
    private function postCategorie(object $body): Response
    {
        $nomeCategoria = $body?->nomeCategoria;

        if (empty($nomeCategoria)) {
            return $this->showInvalidArgs("Nome da categoria não pode ser vázio");
        }

        $categorie = new Categorie($nomeCategoria);
        $success = $this->categorieRepository->add($categorie);

        if (!$success) {
            return $this->showInternalError();
        }

        return $this->showStatus("Categoria cadastrada com sucesso", 201);
    }

    /**
     * @param object $body
     * @return Response
     */
    private function putCategorie(object $body): Response
    {
        $id = filter_var($body->id, FILTER_VALIDATE_INT);
        $nomeCategoria = $body->nomeCategoria;

        if (!$id) {
            return $this->showInvalidArgs("Id inválido");
        }

        if (!$nomeCategoria) {
            return $this->showInvalidArgs("Nome da categoria inválido");
        }

        $categorie = new Categorie($nomeCategoria);
        $categorie->setId($id);

        $success = $this->categorieRepository->update($categorie);

        if (!$success) {
            return $this->showInternalError();
        }

        return $this->showStatus("Categoria editada com sucesso");
    }

    /**
     * @param int $id
     * @return Response
     */
    private function deleteCategorie(int $id): Response
    {
        $result = $this->categorieRepository->remove($id);

        if (!$result) {
            return $this->showInternalError();
        }

        return $this->showStatus("Categoria excluida com sucesso!", 204);
    }
}
