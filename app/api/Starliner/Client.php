<?php

namespace app\api\Starliner;

use app\api\Starliner\Operations\TrainRouteOperation;

class Client
{
    /** @var Credentials */
    private readonly Credentials $credentials;

    /** @var \SoapClient */
    private readonly \SoapClient $soapClient;

    /**
     * @param Credentials $credentials
     * @throws \SoapFault
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;

        $this->soapClient = new \SoapClient($credentials->getWsdlUrl(), [
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'cache_wsdl'  => WSDL_CACHE_DISK,
            'encoding'    => 'utf-8',
            'user_agent'  => 'Train Route PHP Test Task Bot',
            'keep_alive'  => true,
            'trace'       => true,
            'exceptions'  => false
        ]);
    }

    /**
     * Метод отдающий маршрут поезда
     *
     * @return TrainRouteOperation
     */
    public function getTrainRouteOperation(): TrainRouteOperation
    {
        return (new TrainRouteOperation($this->soapClient, $this->credentials));
    }
}