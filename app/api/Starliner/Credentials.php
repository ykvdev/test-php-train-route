<?php

namespace app\api\Starliner;

class Credentials
{
    /** @var string */
    private readonly string $login;

    /** @var string */
    private readonly string $password;

    /** @var string */
    private readonly string $terminal;

    /** @var int */
    private readonly int $representId;

    /**
     * @param string $login
     * @param string $password
     * @param string $terminal
     * @param int $representId
     */
    public function __construct(string $login, string $password, string $terminal, int $representId)
    {
        $this->login = $login;
        $this->password = $password;
        $this->terminal = $terminal;
        $this->representId = $representId;
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