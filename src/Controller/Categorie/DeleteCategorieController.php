<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DeleteCategorieController implements RequestHandlerInterface
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
            return new Response(304, body: json_encode([
                "status" => "Erro ao excluir"
            ]));
        }

        $result = $this->categorieRepository->remove($id);

        if (!$result) {
            return new Response(304, body: json_encode([
                "status" => "Erro ao excluir"
            ]));
        }

        return new Response(200, [
            "Content-Type" => "application-json"
        ], json_encode(["status" => "Ok"]));
    }
}
