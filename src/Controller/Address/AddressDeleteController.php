<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressDeleteController implements RequestHandlerInterface
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);
    
        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }
    
        $result = $this->addressRepository->remove($id);
    
        if (!$result) {
            return Helper::internalError();
        }
    
        return Helper::showStatus(code: 204);
    }
}
