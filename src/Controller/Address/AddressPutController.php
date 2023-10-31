<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Address;

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
        $body = json_decode($request->getBody()->getContents());
        $id = isset($body->id) ? filter_var($body->id, FILTER_VALIDATE_INT) : null;
        
        /** @var ?Address */
        $address = $this->addressRepository->find($id);

        if (empty($address)) {
            return Helper::nothingFound();
        }
        
        $cep = isset($body->cep) ? filter_var($body->cep, FILTER_VALIDATE_INT) : null;
        $numero = isset($body->numero) ? $body->numero : null;
    
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

        $localidade = isset($body->cidade) ? $body->cidade : null;
        $uf = isset($body->uf) ? $body->uf : null;
        $bairro = isset($body->bairro) ? $body->bairro : null;
        $logradouro = isset($body->logradouro) ? $body->logradouro : null;

        if (empty($localidade)) {
            return Helper::invalidRequest("Preencha a cidade.");
        } else if (empty($uf)) {
            return Helper::invalidRequest("Preencha o estado.");
        } else if (empty($bairro)) {
            return Helper::invalidRequest("Preencha o bairro.");
        } else if (empty($logradouro)) {
            return Helper::invalidRequest("Preencha o logradouro.");
        }

        $newAddress = new Address($cep, $uf, $localidade, $bairro, $logradouro, $numero);
        $newAddress->setId($id);

        if(!$this->addressRepository->update($newAddress)) {
            return Helper::internalError();
        }

        return Helper::showStatus("Endereço atualizado com sucesso", 200);
    }
}
