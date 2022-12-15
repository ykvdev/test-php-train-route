<?php

namespace app\api\Starliner;

use app\api\Starliner\Operations\OperationInterface;

class Starliner
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
     * @param OperationInterface $operation
     * @return $this
     * @throws \JsonException
     */
    public function exec(OperationInterface $operation): self
    {
        $params = array_merge($operation->getParams() ?? [], [
            'auth' => [
                'login' => $this->credentials->getLogin(),
                'psw' => $this->credentials->getPassword(),
                'terminal' => $this->credentials->getTerminal(),
                'represent_id' => $this->credentials->getRepresentId(),
                'access_token' => null,
                'language' => null,
                'currency' => null,
            ],
        ]);

        $result = $this->soapClient->__soapCall($operation->getName(), $params);
        $this->request = new Request($this->soapClient->__getLastRequestHeaders(), $this->soapClient->__getLastRequest());
        $this->response = new Response($this->soapClient->__getLastResponseHeaders(), $this->soapClient->__getLastResponse(), $result);

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
}