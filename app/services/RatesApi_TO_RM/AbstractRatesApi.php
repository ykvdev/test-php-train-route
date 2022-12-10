<?php

namespace app\services\RatesApi_TO_RM;

use app\services\ConfigService;
use app\services\GuzzleService;

abstract class AbstractRatesApi
{
    protected ConfigService $config;

    protected GuzzleService $guzzle;

    public function __construct(GuzzleService $guzzle, ConfigService $config)
    {
        $this->guzzle = $guzzle;
        $this->config = $config;
    }

    abstract public function getLatestRates(string $baseSymbol): array;
}