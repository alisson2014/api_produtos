<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use InvalidArgumentException;
use Nyholm\Psr7\Response;
use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressPutController implements RequestHandlerInterface
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
        $id = isset($body->id) ? Helper::filterInt($body->id) : null;

        if (!$id) {
            return Helper::invalidRequest("Id inválido.");
        }
        
        /** @var ?Address */
        $address = $this->addressRepository->find($id);

        if (empty($address)) {
            return Helper::nothingFound();
        }
        
        try {
            $this->cep = Helper::validaCep($body->cep);
            $this->numero = Helper::notNull($body->numero, "Número");
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }
    
        if ($body->isByCep) {
            return $this->updateByCep($id);
        }

        try {
            $this->localidade = Helper::notNull($body->cidade, "Cidade");
            $this->uf = Helper::notNull($body->uf, "Estado");
            $this->bairro = Helper::notNull($body->bairro, "Bairro");
            $this->logradouro = Helper::notNull($body->logradouro, "Logradouro");
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        return $this->updateAddress($id);
    }

    private function updateByCep(int $id): Response
    {
        $address = $this->addressRepository->findByCep($this->cep);

        if (empty($address)) {
            return Helper::invalidRequest("CEP não encontrado na base de dados dos correios!");
        }

        $this->uf = $address["uf"];
        $this->localidade = $address["localidade"];
        $this->bairro = $address["bairro"];
        $this->logradouro = $address["logradouro"];

        return $this->updateAddress($id);
    }

    private function updateAddress(int $id): Response
    {
        $newAddress = new Address(
            $this->cep, 
            $this->uf, 
            $this->localidade, 
            $this->bairro, 
            $this->logradouro, 
            $this->numero
        );
        $newAddress->setId($id);

        if(!$this->addressRepository->update($newAddress)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Endereço atualizado com sucesso", 200);
    }
}
