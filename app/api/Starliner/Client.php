<?php

namespace app\api\Starliner;

class Client
{
    /** @var Credentials */
    protected readonly Credentials $credentials;

    /** @var \SoapClient */
    protected readonly \SoapClient $soapClient;

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
}