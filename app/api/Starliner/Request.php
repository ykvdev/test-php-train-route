<?php

namespace app\api\Starliner;

class Request
{
    /** @var string */
    private string $headers;

    /** @var string */
    private string $body;

    /**
     * @param string|null $headers
     * @param string $body
     */
    public function __construct(?string $headers, string $body)
    {
        $this->headers = trim($headers);
        $this->body = trim($body);
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}