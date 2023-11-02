<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

use InvalidArgumentException;
use Produtos\Action\Domain\Model\Address;
use Produtos\Action\Infrastructure\Repository\AddressRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class AddressPutController implements RequestHandlerInterface
{
    public function __construct(
        private AddressRepository $addressRepository
    ) {   
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {   
        $body = Helper::getBody($request);
        $id = isset($body->id) ? Helper::filterInt($body->id) : null;
        
        /** @var ?Address */
        $address = $this->addressRepository->find($id);

        if (empty($address)) {
            return Helper::nothingFound();
        }
        
        try {
            $cep = Helper::validaCep($body->cep);
            $numero = Helper::notNull($body->numero);
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }
    
        if ($body->isByCep) {
            $address = $this->addressRepository->findByCep($cep);

            if (empty($address)) {
                return Helper::invalidRequest("CEP não encontrado na base de dados dos correios!");
            }
    
            $uf = $address["uf"];
            $localidade = $address["localidade"];
            $bairro = $address["bairro"];
            $logradouro = $address["logradouro"];
    
            $newAddress = new Address($cep, $uf, $localidade, $bairro, $logradouro, $numero);
            $newAddress->setId($id);
    
            if(!$this->addressRepository->update($newAddress)) {
                return Helper::internalError();
            }
    
            return Helper::showStatus("Endereço atualizado com sucesso", 200);
        }

        try {
            $localidade = Helper::notNull($body->cidade);
            $uf = Helper::notNull($body->uf);
            $bairro = Helper::notNull($body->bairro);
            $logradouro = Helper::notNull($body->logradouro);
        } catch (InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        $newAddress = new Address($cep, $uf, $localidade, $bairro, $logradouro, $numero);
        $newAddress->setId($id);

        if(!$this->addressRepository->update($newAddress)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Endereço atualizado com sucesso", 200);
    }
}
