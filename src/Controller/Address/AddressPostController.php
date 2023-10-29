<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AddressPostController implements RequestHandlerInterface
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $cep = $body["cep"];

        if (empty($cep) || strlen((string)$cep) < 8) {
            return Helper::invalidRequest("CEP inválido!");
        }

        return Helper::showResponse($this->addressRepository->findByCep($cep));
    }
}
