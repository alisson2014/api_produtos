<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Client;

use Produtos\Action\Infrastructure\Repository\ClientRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ClientDeleteController implements RequestHandlerInterface
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $id = filter_var($queryParams["id"], FILTER_VALIDATE_INT);

        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        $result = $this->clientRepository->remove($id);

        if (!$result) {
            return Helper::internalError();
        }

        return Helper::showStatus(code: 204);
    }
}
