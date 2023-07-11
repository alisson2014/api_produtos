<?php

namespace Produtos\Action\Controller\Product;

use Produtos\Action\Infrastructure\Repository\ProductRepository;
use Produtos\Action\Service\Show;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ProductDeleteController implements RequestHandlerInterface
{
    use Show;

    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);

        if (!$id) {
            return $this->showInvalidArgs("Id inválido");
        }

        $result = $this->productRepository->remove($id);

        if (!$result) {
            return $this->showInternalError();
        }

        return $this->showStatus("Produto excluido com sucesso!", 204);
    }
}
