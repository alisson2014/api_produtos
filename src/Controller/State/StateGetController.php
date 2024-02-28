<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\State;

use Nyholm\Psr7\Response;
use Produtos\Action\Infrastructure\Repository\StateRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class StateGetController implements RequestHandlerInterface 
{
    private int $id;

    public function __construct(
        private StateRepository $stateRepository
    ){
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;
        $only = $queryParams["only"] ?? "";

        if (is_null($id)) {
            return $this->listStates($only);
        }

        try {
            $this->id = Helper::validaId($id);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        return $this->findState();
    }

    private function listStates(string $only = ""): Response
    {
        return Helper::showResponse($this->stateRepository->all(false, $only));
    }

    private function findState(): Response
    {
        $state = $this->stateRepository->find($this->id, false);

        return empty($state) 
                ? Helper::nothingFound() 
                : Helper::showResponse($state);
    }
}
