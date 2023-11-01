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
    private ?int $cep;
    private ?string $numero;
    private ?string $localidade;
    private ?string $uf;
    private ?string $bairro;
    private ?string $logradouro;
    
    public function __construct(
        private AddressRepository $addressRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);
        $this->cep = isset($body->cep) ? Helper::filterInt($body->cep) : null;
        $this->numero = isset($body->numero) ? $body->numero : null;

        if (empty($this->cep) || strlen(strval($this->cep)) < 8) {
            return Helper::invalidRequest("CEP inválido!");
        } else if (empty($this->numero)) {
            return Helper::invalidRequest("Insira o número da residência!");
        }

        if ($body->isByCep) {
            return $this->addByCep();
        }

        $this->localidade = isset($body->cidade) ? $body->cidade : null;
        $this->uf = isset($body->uf) ? $body->uf : null;
        $this->bairro = isset($body->bairro) ? $body->bairro : null;
        $this->logradouro = isset($body->logradouro) ? $body->logradouro : null;

        if (empty($this->localidade)) {
            return Helper::invalidRequest("Preencha a cidade.");
        } else if (empty($this->uf)) {
            return Helper::invalidRequest("Preencha o estado.");
        } else if (empty($this->bairro)) {
            return Helper::invalidRequest("Preencha o bairro.");
        } else if (empty($this->logradouro)) {
            return Helper::invalidRequest("Preencha o logradouro.");
        }

        return $this->addAddress();
    }

    private function addByCep(): ResponseInterface {
        $address = $this->addressRepository->findByCep($this->cep);

        if (empty($address)) {
            return Helper::invalidRequest("CEP não encontrado na base de dados dos correios!");
        }

        $this->uf = $address["uf"];
        $this->localidade = $address["localidade"];
        $this->bairro = $address["bairro"];
        $this->logradouro = $address["logradouro"];

        return $this->addAddress();
    }

    private function addAddress(): ResponseInterface {
        $address = new Address(
            $this->cep,
            $this->uf,
            $this->localidade,
            $this->bairro,
            $this->logradouro,
            $this->numero
        );

        if (!$this->addressRepository->add($address)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Endereço cadastrado com sucesso", 201);
    }
}
