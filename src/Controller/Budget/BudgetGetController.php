<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Budget;

use Produtos\Action\Domain\Model\Budget;
use Produtos\Action\Infrastructure\Repository\BudgetRepository;
use Produtos\Action\Service\Helper;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

final class BudgetGetController implements RequestHandlerInterface
{
    public function __construct(
        private  BudgetRepository $budgetRepository
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request?->getQueryParams();
        $id = $queryParams["id"] ?? null;

        if (is_null($id)) {
            return $this->listBudgets();
        }

        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            return Helper::invalidRequest("Id inválido");
        }

        return $this->findBuget($id);
    }

    /** @return ResponseInterface */
    private function listBudgets(): ResponseInterface
    {
        $allBudgets = $this->budgetRepository->all();

        if (empty($allBudgets)) {
            return Helper::nothingFound();
        }

        $budgetList = array_map(function (Budget $budget): array {
            return [
                "id" => $budget->id,
                "cliente" => $budget->client,
                "produtos" => $budget->product,
                "total" => $budget->total
            ];
        }, $allBudgets);

        return Helper::showResponse($budgetList);
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    private function findBuget(int $id): ResponseInterface
    {
        $budget = $this->budgetRepository->find($id);

        if (empty($budget)) {
            return Helper::nothingFound();
        }

        return Helper::showResponse($budget);
    }
}
