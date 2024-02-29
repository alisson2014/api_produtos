<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\State;

use Produtos\Action\Infrastructure\Repository\StateRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class StatePatchController implements RequestHandlerInterface 
{
    public function __construct(
        private StateRepository $stateRepository
    ){
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = Helper::getBody($request);

        try {
            $active = $body->active;
            if($active !== "s" && $active !== "n") {
                throw new \InvalidArgumentException("Ativo deve ser um campo do tipo 's' ou 'n'");
            }
            $stateId = Helper::validaId($body->id);
        } catch (\InvalidArgumentException $ex) {
            return Helper::invalidRequest($ex->getMessage());
        }

        $state = $this->stateRepository->find($stateId);
        $state->setActive($active);

        if(!$this->stateRepository->patch($state)) {
            return Helper::internalError();
        }
        
        $msg = $active === "s" ? "ativado" : "desativado";
        return Helper::showStatus("Estado {$state->descricao} {$msg} com sucesso.");
    }
}
