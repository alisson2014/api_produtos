<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressPostController implements RequestHandlerInterface
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());

        if ($body->isByCep) {
            $cep = isset($body->cep) ? filter_var($body->cep, FILTER_VALIDATE_INT) : null;
            $numero = isset($body->numero) ? $body->numero : null;

            if (empty($cep) || strlen((string)$cep) < 8) {
                return Helper::invalidRequest("CEP inválido!");
            } else if (empty($numero)) {
                return Helper::invalidRequest("Insira o número da residência!");
            }

            $address = $this->addressRepository->findByCep($cep);

            if (empty($address)) {
                return Helper::invalidRequest("CEP não encontrado na base de dados dos correios!");
            }

            $address = new Address(
                $cep,
                $address["uf"],
                $address["localidade"],
                $address["bairro"],
                $address["logradouro"],
                $numero
            );

            $this->addressRepository->add($address);
        }

        if (empty($cep) || strlen((string)$cep) < 8) {
            return Helper::invalidRequest("CEP inválido!");
        }

        return Helper::showResponse($this->addressRepository->findByCep($cep));
    }
}
