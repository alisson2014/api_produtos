<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use Nyholm\Psr7\Response;
use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressGetController implements RequestHandlerInterface
{
    private int $id;
    public function __construct(
        private AddressRepository $adressRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listAddress();
        }

        $this->id = Helper::filterInt($id);
        if (!$this->id) {
            return Helper::invalidRequest("Id inválido");
        }

        return $this->findAddress();
    }

    private function listAddress(): Response
    {
        $allAddress = $this->adressRepository->all(false);

        return empty($allAddress) 
                ? Helper::nothingFound() 
                : Helper::showResponse($allAddress);
    }

    private function findAddress(): Response
    {
        $address = $this->adressRepository->find($this->id, false);

        return empty($address) 
                ? Helper::nothingFound() 
                : Helper::showResponse($address);
    }
}
