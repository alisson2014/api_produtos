<?php

declare(strict_types=1);

namespace Produtos\Action\Controller\Product;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ProductOptionsController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [
            "Access-Control-Allow-Origin" => "http://localhost:3000",
            "Access-Control-Allow-Headers" => "*",
            "Access-Control-Allow-Methods" => "OPTIONS, GET, POST, PUT, DELETE",
            "Content-Type" => "application/json; charset=UTF-8",
        ]);
    }
}
