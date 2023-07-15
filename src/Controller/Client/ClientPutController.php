<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Client;

use Produtos\Action\Domain\Model\Client;
use Produtos\Action\Infrastructure\Repository\ClientRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ClientPutController implements RequestHandlerInterface
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents());
        $id = isset($body->id) ? filter_var($body->id, FILTER_VALIDATE_INT) : null;
        $nomeCliente = isset($body->nomeCliente) ? $body->nomeCliente : null;
        $dataOrcamento = isset($body->dataOrcamento)
            ? filter_var($body->dataOrcamento, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^\d{2}-\d{2}-\d{4}$/"]])
            : null;

        $error = "";

        if (!$id) {
            $error = "Id inválido.";
        } elseif (empty($nomeCliente) || !is_string($nomeCliente)) {
            $error = "Nome do cliente inválido.";
        } elseif (!$dataOrcamento) {
            $error = "O campo data está inválido, deve estar no formato: dd-mm-yyyy";
        }

        if (!empty($error)) {
            return Helper::invalidRequest($error);
        }

        $data = new \DateTime($dataOrcamento);
        $client = new Client($nomeCliente, $data);
        $client->setId($id);

        $success = $this->clientRepository->update($client);

        if (!$success) {
            return Helper::internalError();
        }

        return Helper::showStatus("Orçamento editado com sucesso");
    }
}
