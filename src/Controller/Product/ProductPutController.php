<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Product;

use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductPutController implements RequestHandlerInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);

        try {
            $id = Helper::validaId($body->id);
            $idCategoria = Helper::validaId($body->idCategoria);
            $valor = Helper::validaValor($body->valor);
            $produto = Helper::notNull($body->nomeProduto);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        $categorie = $this->productRepository->findCategorie($idCategoria);

        if(empty($categorie)) {
            return Helper::showStatus("Categoria inválida para cadastro de produto.", 422, "error");
        }

        $product = new Product($produto, $valor, $categorie);
        $product->setId($id);

        if (!$this->productRepository->update($product)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Produto editado com sucesso");
    }
}
