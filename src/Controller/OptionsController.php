<?php

declare(strict_types=1);

namespace Produtos\Action\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;

abstract class OptionsController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [
            "Access-Control-Allow-Origin" => "http://localhost:3000",
            "Access-Control-Allow-Headers" => "*",
            "Access-Control-Allow-Methods" => "OPTIONS, GET, POST, PATCH, PUT, DELETE",
            "Content-Type" => "application/json; charset=UTF-8",
        ]);
    }
}
