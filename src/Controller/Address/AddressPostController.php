<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use InvalidArgumentException;
use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Domain\Model\City;
use Produtos\Action\Domain\Model\State;
use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressPostController implements RequestHandlerInterface
{
    private ?string $cep;
    private ?string $numero;
    private ?int $cidade_id;
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
        
        try {
            $this->cep = Helper::validaCep($body->cep);
            $this->numero = Helper::notNull($body->numero, "Número");
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        if ($body->isByCep) {
            return $this->addByCep();
        }

        try {
            $this->cidade_id = Helper::notNull($body->cidade_id, "Cidade");
            $this->uf = Helper::notNull($body->uf, "Estado");
            $this->addressRepository->validState($this->uf);
            $this->bairro = Helper::notNull($body->bairro, "Bairro");
            $this->logradouro = Helper::notNull($body->logradouro, "Logradouro");
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        return $this->addAddress();
    }

    private function addByCep(): ResponseInterface {
        $address = $this->addressRepository->findByCep($this->cep);

        if (empty($address)) {
            return Helper::invalidRequest("CEP não encontrado na base de dados dos correios!");
        }

        $this->cep = $address["cep"];
        $this->uf = $address["uf"];
        $this->cidade_id = $address["localidade"];
        $this->bairro = $address["bairro"];
        $this->logradouro = $address["logradouro"];

        return $this->addAddress();
    }

    private function addAddress(): ResponseInterface {
        $city = $this->addressRepository->findCity($this->cidade_id);

        if(is_null($city)) {
            return Helper::showStatus("Necessário cadastrar a cidade na base de dados.", 422, "error");
        }

        $city = new City($city, new State($this->uf));
        $city->setId($this->cidade_id);

        $address = new Address(
            $this->cep,
            $city,
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
