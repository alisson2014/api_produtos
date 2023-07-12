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
        $id = isset($body->id) ? filter_var($body->id, FILTER_VALIDATE_INT) : null;
        $idCategoria = isset($body->idCategoria) ? filter_var($body->idCategoria, FILTER_VALIDATE_INT) : null;
        $valor = isset($body->valor) ? filter_var($body->valor, FILTER_VALIDATE_FLOAT) : null;
        $produto = isset($body->nomeProduto) ? $body->nomeProduto : null;

        $error = "";

        if (!$id || !$idCategoria) {
            $notIsCategory = $idCategoria ?: "da categoria";
            $error = "Id {$notIsCategory} inválido.";
        } elseif (empty($produto) || !is_string($produto)) {
            $error = "Nome do produto inválido.";
        } elseif ($valor > 0 && $valor <= (10 ** 8)) {
            $error = "Valor inválido, valor deve ser maior que 0 e menor que 100 milhões.";
        }

        if (!empty($error)) {
            return Helper::invalidRequest($error);
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
