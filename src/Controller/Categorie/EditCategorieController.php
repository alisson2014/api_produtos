<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EditCategorieController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());
        $id = filter_var($body->id, FILTER_VALIDATE_INT);
        $nomeCategoria = $body->nomeCategoria;

        if ($nomeCategoria === "" || !$id) {
            return new Response(304, body: json_encode([
                "status" => "Erro"
            ]));
        }

        $categorie = new Categorie($nomeCategoria);
        $categorie->setId($id);

        $success = $this->categorieRepository->update($categorie);

        if (!$success) {
            return new Response(304, body: json_encode([
                "status" => "Erro"
            ]));
        }

        return new Response(200, [
            "Content-Type" => "application-json"
        ], json_encode(["status" => "Editado"]));
    }
}
