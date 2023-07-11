<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use Nyholm\Psr7\Response;

final class Helper
{
    private const ACCESS_HEADERS = [
        "Access-Control-Allow-Origin" => "http://localhost:3000",
        "Access-Control-Allow-Headers" => "*",
        "Content-Type" => "application/json; charset=UTF-8",
    ];

    /** @param array|object $list */
    public static function showResponse(mixed $list): Response
    {
        return new Response(200, self::ACCESS_HEADERS, json_encode($list));
    }

    public static function nothingFound(): Response
    {
        return self::showStatus("A busca na base de dados não retornou nenhum registro", type: "info");
    }

    public static function invalidRequest(string $message): Response
    {
        return self::showStatus($message, 400, "error");
    }

    public static function internalError(): Response
    {
        return self::showStatus("Erro ao tentar salvar na base de dados", 500, "error");
    }

    public static function showStatus(
        string $message = "",
        int $code = 200,
        string $type = "success"
    ): Response {
        $response = [
            "status" => $type,
            "message" => $message
        ];
        return new Response($code, self::ACCESS_HEADERS, json_encode($response));
    }
}
