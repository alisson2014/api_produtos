<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Client;

use DateTime;
use Produtos\Action\Domain\Model\Client;
use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Produtos\Action\Infrastructure\Repository\ClientRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Server\RequestHandlerInterface;

final class ClientPostController implements RequestHandlerInterface
{
    public function __construct(
        private ClientRepository $clientRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dateRegex = "/^\d{2}-\d{2}-\d{4}$/";
        $body = json_decode($request->getBody()->getContents());
        $nomeCliente = isset($body->nomeCliente) ? $body->nomeCliente : null;
        $dataOrcamento = isset($body->dataOrcamento)
            ? filter_var($body->dataOrcamento, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $dateRegex]])
            : null;

        $error = "";

        if (empty($nomeCliente) || !is_string($nomeCliente)) {
            $error = "Nome do cliente inválido.";
        } elseif (!$dataOrcamento) {
            $error = "O campo data está inválido, deve estar no formato: dd-mm-yyyy";
        }

        if (!empty($error)) {
            return Helper::invalidRequest($error);
        }

        $data = new DateTime($dataOrcamento);
        $product = new Client($nomeCliente, $data);
        $success = $this->clientRepository->add($product);

        if (!$success) {
            return Helper::internalError();
        }

        return Helper::showStatus("Orçamento cadastrado com sucesso", 201);
    }
}
