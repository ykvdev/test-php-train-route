<?php

namespace app\api\Starliner;

class Credentials
{
    /** @var string */
    private string $wsdlUrl;

    /** @var string */
    private string $login;

    /** @var string */
    private string $password;

    /** @var string */
    private string $terminal;

    /** @var int */
    private int $representId;

    /**
     * @param string $wsdlUrl
     * @param string $login
     * @param string $password
     * @param string $terminal
     * @param int $representId
     */
    public function __construct(string $wsdlUrl, string $login, string $password, string $terminal, int $representId)
    {
        $this->wsdlUrl = $wsdlUrl;
        $this->login = $login;
        $this->password = $password;
        $this->terminal = $terminal;
        $this->representId = $representId;
    }

    /**
     * @return string
     */
    public function getWsdlUrl(): string
    {
        return $this->wsdlUrl;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getTerminal(): string
    {
        return $this->terminal;
    }

    /**
     * @return int
     */
    public function getRepresentId(): int
    {
        return $this->representId;
    }
}