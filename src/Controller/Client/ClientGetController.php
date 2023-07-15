<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Client;

use Produtos\Action\Domain\Model\Client;
use Produtos\Action\Infrastructure\Repository\ClientRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class ClientGetController implements RequestHandlerInterface
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listClients();
        }

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        return $this->findClient($id);
    }

    /** @return ResponseInterface */
    private function listClients(): ResponseInterface
    {
        $allClients = $this->clientRepository->all();

        if (empty($allClients)) {
            return Helper::nothingFound();
        }

        $clientList = array_map(function (Client $client): array {
            return [
                "id" => $client->id,
                "nomeCategoria" => $client->nomeCliente,
                "dataOrçamento" => $client->dataOrcamento->format("d-m-Y")
            ];
        }, $allClients);

        return Helper::showResponse($clientList);
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    private function findClient(int $id): ResponseInterface
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            return Helper::nothingFound();
        }

        $formatClient = [
            "id" => $client->id,
            "nomeCliente" => $client->nomeCliente,
            "dataOrcamento" => $client->dataOrcamento->format("d-m-Y")
        ];

        return Helper::showResponse($formatClient);
    }
}
