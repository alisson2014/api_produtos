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
        $id = Helper::filterInt($queryParams["id"]);
    
        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }
    
        if (!$this->addressRepository->remove($id)) {
            return Helper::internalError();
        }
    
        return Helper::showStatus(code: 204);
    }
}
