<?php

namespace app\services\RatesApi_TO_RM;

class ExchangeRatesApi extends AbstractRatesApi
{
    private const API_URL_GET_LATEST = 'https://api.exchangeratesapi.io/latest?base=%s&symbols=%s';

    public function getLatestRates(string $baseSymbol): array
    {
        $availableSymbols = $this->config->get('services.rates_api.available_symbols');
        unset($availableSymbols[array_search($baseSymbol, $availableSymbols)]); // remove base symbol

        $apiUrl = sprintf(self::API_URL_GET_LATEST, $baseSymbol, implode(',', $availableSymbols));
        $response = $this->guzzle->sendGetRequest($apiUrl);
        if(!$response) {
            throw new \RuntimeException('API request failed');
        }

        $response = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        if(isset($response['error'])) {
            throw new \RuntimeException('API error: ' . $response['error']);
        }

        return $response['rates'];
    }
}