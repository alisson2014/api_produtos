<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Nyholm\Psr7\Response;

trait Show
{
    private const JSON_CONTENT = [
        "Content-Type" => "application-json"
    ];

    /** @param array|object $list */
    private function showResponse(mixed $list): Response
    {
        return new Response(200, self::JSON_CONTENT, json_encode($list));
    }

    /**
     * @param string $args
     * @param int $code
     */
    private function showStatusOk(
        string $args,
        int $code = 200
    ): Response {
        $okResponse = $this->makeResponse("ok", $args);
        return new Response($code, self::JSON_CONTENT, json_encode($okResponse));
    }

    /** @param string $args */
    private function showInvalidArgs(string $args): Response
    {
        $errorResponse = $this->makeResponse("error", $args);
        return new Response(400, self::JSON_CONTENT, json_encode($errorResponse));
    }

    private function showInternalError(): Response
    {
        $errorResponse = $this->makeResponse("error", "Erro ao tentar salvar/excluir na base de dados");
        return new Response(500, self::JSON_CONTENT, json_encode($errorResponse));
    }

    /**
     * @param string $status
     * @param string $message
     * @return array
     */
    private function makeResponse(
        string $status,
        string $message
    ): array {
        return [
            "status" => $status,
            "message" => $message
        ];
    }
}
