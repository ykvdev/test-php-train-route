<?php

namespace app\api\Starliner;

class Response
{
    /** @var string|null */
    private readonly ?string $headers;

    /** @var string|null */
    private readonly ?string $body;

    /** @var mixed */
    private readonly mixed $result;

    /** @var string|null */
    private readonly ?string $error;

    /**
     * @param string|null $headers
     * @param string|null $body
     * @param mixed $result
     * @throws \JsonException
     */
    public function __construct(?string $headers, ?string $body, mixed $result)
    {
        $this->headers = $headers ? trim($headers) : null;
        $this->body = $body ? trim($body) : null;

        if($result instanceof \SoapFault) {
            $this->result = null;
            $this->error = $result->getMessage();
        } else {
            $this->result = is_array($result) || is_object($result)
                ? json_decode(json_encode($result, flags: JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR) // convert object to array
                : $result;
            $this->error = null;
        }
    }

    /**
     * @return string|null
     */
    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $path
     * @return mixed
     */
    public function getResult(?string $path = null): mixed
    {
        return $path && is_array($this->result)
            ? getArrayItemByPath($this->result, $path)
            : $this->result;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}