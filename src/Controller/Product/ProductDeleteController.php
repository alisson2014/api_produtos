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
        
        try {
            $id = Helper::validaId($queryParams["id"]);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        if (!$this->productRepository->remove($id)) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
