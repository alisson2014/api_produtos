<?php

declare(strict_types=1);

namespace Produtos\Tests\Integration\Api;
use PHPUnit\Framework\TestCase;

class ApiCategorieTest extends TestCase
{
    private const API_URL = 'http://localhost:8080/categorias';
    
    public function testApiRestMustReturnCategorieArray(): void
    {
        $response = file_get_contents(self::API_URL);

        self::assertStringContainsString('200 OK', $http_response_header[0]);
        self::assertIsArray(json_decode($response));
    }
}