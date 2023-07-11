<?php

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
        $body = json_decode($request->getBody()->getContents());
        $id = filter_var($body->id, FILTER_VALIDATE_INT);
        $produto = $body->nomeProduto;
        $valor = $body->valor;
        $idCategoria = $body->idCategoria;

        if (!$id) {
            return Helper::invalidRequest("Id inválido.");
        }

        $product = new Product($produto, $valor, $idCategoria);
        $product->setId($id);
        $success = $this->productRepository->update($product);

        if (!$success) {
            return Helper::internalError();
        }

        return Helper::showStatus("Produto editado com sucesso");
    }
}
