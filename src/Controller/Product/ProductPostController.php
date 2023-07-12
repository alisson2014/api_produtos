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
        $body = json_decode($request->getBody()->getContents());
        $produto = isset($body->nomeProduto) ? $body->nomeProduto : null;
        $valor = isset($body->valor) ? filter_var($body->valor, FILTER_VALIDATE_FLOAT) : null;
        $idCategoria = isset($body->idCategoria) ? filter_var($body->idCategoria, FILTER_VALIDATE_INT) : null;

        $error = "";

        if (empty($produto) || !is_string($produto)) {
            $error = "Nome do produto inválido.";
        } elseif ($valor > 0 && $valor <= (10 ** 8)) {
            $error = "Valor inválido, valor deve ser maior que 0 e menor que 100 milhões.";
        } else if (!$idCategoria) {
            $error = "Id inválido.";
        }

        if (!empty($error)) {
            return Helper::invalidRequest($error);
        }

        $product = new Product($produto, $valor, $idCategoria);
        $success = $this->productRepository->add($product);

        if (!$success) {
            return Helper::internalError();
        }

        return Helper::showStatus("Categoria cadastrada com sucesso", 201);
    }
}
