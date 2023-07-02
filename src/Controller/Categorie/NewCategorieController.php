<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Categorie;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

class NewCategorieController implements RequestHandlerInterface
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dados = json_decode($request->getBody()->getContents());
        $nomeCategoria = $dados->nomeCategoria;

        if (is_null($nomeCategoria) || $nomeCategoria === "") {
            return new Response(304, body: json_encode([
                "status" => "Erro ao cadastrar"
            ]));
        }

        $categorie = new Categorie($nomeCategoria);
        $success = $this->categorieRepository->add($categorie);

        if (!$success) {
            return new Response(304, body: json_encode([
                "status" => "Erro ao cadastrar"
            ]));
        }

        return new Response(201, [
            "Content-Type" => "application-json"
        ], json_encode(["status" => "Registrado"]));
    }
}
