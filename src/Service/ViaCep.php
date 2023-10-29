<?php

declare(strict_types=1);

namespace Produtos\Action\Service;

final class ViaCep
{
    public static function findByCep(string $cep): ?array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://viacep.com.br/ws/{$cep}/json/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $resArray = json_decode($response, true);

        return isset($resArray["cep"]) ? $resArray : null;
    }
}
