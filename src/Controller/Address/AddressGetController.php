<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressGetController implements RequestHandlerInterface
{
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

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        return $this->findAddress($id);
    }

    /** @return ResponseInterface */
    private function listAddress(): ResponseInterface
    {
        $allAddress = $this->adressRepository->all(false);

        if (empty($allAddress)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($allAddress);
    }

    private function findAddress(int $id): ResponseInterface
    {
        $address = $this->adressRepository->find($id, false);

        if (empty($address)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($address);
    }
}
