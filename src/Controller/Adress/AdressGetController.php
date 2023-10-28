<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Adress;

use Produtos\Action\Domain\Model\Adress;
use Produtos\Action\Infrastructure\Repository\AdressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AdressGetController implements RequestHandlerInterface
{
    public function __construct(
        private AdressRepository $adressRepository
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
        $allAddress = $this->adressRepository->all();

        if (empty($allAddress)) {
            return Helper::nothingFound();
        }

        $addressList = array_map(function (Adress $address): array {
            return $this->compactAddress($address);
        }, $allAddress);

        return Helper::showResponse($addressList);
    }

    private function findAddress(int $id): ResponseInterface
    {
        $address = $this->adressRepository->find($id, false);

        if (empty($address)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($address);
    }

    private function compactAddress(Adress $address): array 
    {
        $id = $address->id;
        $cidade = $address->cidade;
        $bairro = $address->bairro;
        $rua = $address->rua;
        $numero = $address->numero;
        
        return compact(["id", "cidade", "bairro", "rua", "numero"]);
    }
}
