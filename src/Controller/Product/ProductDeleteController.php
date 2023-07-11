<?php

namespace Produtos\Action\Controller\Product;

use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductDeleteController implements RequestHandlerInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);

        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        $result = $this->productRepository->remove($id);

        if (!$result) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
