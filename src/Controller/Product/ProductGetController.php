<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Product;

use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductGetController implements RequestHandlerInterface
{
    use Show;

    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listProducts();
        }

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return $this->showInvalidArgs("Id inválido.");
        }

        return $this->findProduct($id);
    }

    /** @return ResponseInterface */
    private function listProducts(): ResponseInterface
    {
        $productList = array_map(function (Product $product): array {
            return [
                "id" => $product->id,
                "nomeProduto" => $product->nomeProduto,
                "categoria" => $product->categoria,
                "valor" => $product->valor,
                "idCategoria" => $product->idCategoria
            ];
        }, $this->productRepository->all());

        return $this->showResponse($productList);
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    private function findProduct(int $id): ResponseInterface
    {
        $categorie = $this->productRepository->find($id);
        return $this->showResponse($categorie);
    }
}
