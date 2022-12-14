<?php

namespace app\services\StarlinerApiService;

use app\api\Starliner\Client;
use app\api\Starliner\Credentials;
use app\services\ConfigService;
use app\services\TimerService;
use DI\Container;

class StarlinerApiService extends Client
{
    /** @var Container */
    private readonly Container $di;

    /** @var ConfigService */
    private readonly ConfigService $config;

    /**
     * @param Container $di
     * @param ConfigService $config
     * @param TimerService $timer
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \SoapFault
     */
    public function __construct(Container $di, ConfigService $config)
    {
        $this->di = $di;
        $this->config = $config;

        $apiConfig = $this->config->get('services.starliner_api.auth');
        parent::__construct(new Credentials(
            $apiConfig['wsdl_url'],
            $apiConfig['login'],
            $apiConfig['password'],
            $apiConfig['terminal'],
            $apiConfig['represent_id'],
        ));
    }

    /**
     * Метод отдающий маршрут поезда
     *
     * @return TrainRouteOperation
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function trainRouteOperation(): TrainRouteOperation
    {
        return $this->di->make(TrainRouteOperation::class, [
            'soapClient' => $this->soapClient,
            'credentials' => $this->credentials
        ]);
    }
}