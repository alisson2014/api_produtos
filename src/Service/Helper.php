<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

use InvalidArgumentException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

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

    public static function getBody(ServerRequestInterface $req): mixed
    {
       return json_decode($req->getBody()->getContents()); 
    }

    public static function filterInt(mixed $id): int
    {
        return filter_var($id, FILTER_VALIDATE_INT) ? : 0;
    }

    /** @throws InvalidArgumentException */
    public static function validaId(mixed $id): int
    {
        $id = self::filterInt($id);

        if (!$id || $id <= 0) {
            throw new InvalidArgumentException("Id inválido");
        }

        return $id;
    }

    /** @throws InvalidArgumentException */
    public static function validaCep(string $cep): string
    {
        $cep = isset($cep) ? $cep : null;

        if (empty($cep) || strlen($cep) < 8) {
            throw new InvalidArgumentException("CEP inválido!");
        }

        return $cep;
    }

    /** @throws InvalidArgumentException */
    public static function validaValor(mixed $value): float
    {   
        $value = isset($value) ? filter_var($value, FILTER_VALIDATE_FLOAT) : null;

        if (empty($value) || ($value <= 0 || $value > (10 ** 8))) {
            throw new InvalidArgumentException("Valor inválido, valor deve ser maior que 0 e menor que 100 milhões.");
        }

        return $value;
    }   

    /** @throws InvalidArgumentException */
    public static function notNull(mixed $value, string $var = "Valor"): mixed
    {
        $value = isset($value) ? $value : null;

        if (empty($value)) {
            throw new InvalidArgumentException("{$var} não pode ser vázio!");
        }

        return $value;
    }
}
