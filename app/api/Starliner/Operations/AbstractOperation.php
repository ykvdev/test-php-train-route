<?php

namespace app\api\Starliner\Operations;

use app\api\Starliner\Credentials;
use app\api\Starliner\Request;
use app\api\Starliner\Response;

abstract class AbstractOperation
{
    /** @var \SoapClient */
    protected readonly \SoapClient $soapClient;

    /** @var Credentials */
    protected readonly Credentials $credentials;

    /** @var Request */
    private Request $request;

    /** @var Response */
    private Response $response;

    /**
     * @param \SoapClient $soapClient
     * @param Credentials $credentials
     */
    public function __construct(\SoapClient $soapClient, Credentials $credentials)
    {
        $this->soapClient = $soapClient;
        $this->credentials = $credentials;
    }

    /**
     * @return $this
     */
    public function exec(): self
    {
        $this->soapClient->__soapCall($this->getName(), $this->getParams());
        $this->request = new Request($this->soapClient->__getLastRequestHeaders(), $this->soapClient->__getLastRequest());
        $this->response = new Response($this->soapClient->__getLastResponseHeaders(), $this->soapClient->__getLastResponse());

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * @return array
     */
    abstract protected function getParams(): array;
}