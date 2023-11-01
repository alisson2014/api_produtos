<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Product;

use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Product;
use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductGetController implements RequestHandlerInterface
{
    private int $id;
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

        $this->id = Helper::filterInt($id);
        if (!$this->id) {
            return Helper::invalidRequest("Id inválido.");
        }

        return $this->findProduct();
    }

    private function listProducts(): Response
    {
        $productList = array_map(function (Product $product): array {
            return $this->compactProduct($product);
        }, $this->productRepository->all());

        return empty($productList) 
                ? Helper::nothingFound() 
                : Helper::showResponse($productList);
    }

    private function findProduct(): Response
    {
        $product = $this->productRepository->find($this->id);

        if (empty($product)) {
            return Helper::nothingFound();
        }
   
        return Helper::showResponse($this->compactProduct($product));
    }

    private function compactProduct(Product $product): array 
    {
        $id = $product->id;
        $nomeProduto = $product->nomeProduto;
        $valor = $product->valor;
        $categoria = $this->productRepository->findCategorie($product->idCategoria, false);
    
        return compact(["id", "nomeProduto", "valor", "categoria"]);
    }
}
