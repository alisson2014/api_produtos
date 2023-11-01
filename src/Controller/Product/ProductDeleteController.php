<?php

declare(strict_types=1);

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
        $id = Helper::filterInt($queryParams["id"]);

        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        if (!$this->productRepository->remove($id)) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
