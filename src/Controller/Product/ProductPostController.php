<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Product;

use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductPostController implements RequestHandlerInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);

        try {
            $produto = Helper::notNull($body->nomeProduto, "Nome do produto");
            $valor = Helper::validaValor($body->valor);
            $idCategoria = Helper::validaId($body->idCategoria);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        $categorie = $this->productRepository->findCategorie($idCategoria);

        if(empty($categorie)) {
            return Helper::showStatus("Categoria inválida para cadastro de produto.", 422, "error");
        }

        $product = new Product($produto, $valor, $categorie);

        if (!$this->productRepository->add($product)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Produto cadastrado com sucesso", 201);
    }
}
