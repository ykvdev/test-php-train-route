<?php

namespace app\api\Starliner;

class Response
{
    /** @var string */
    private string $headers;

    /** @var string */
    private string $body;

    /** @var array */
    private array $bodyDecoded;

    /**
     * @param string|null $headers
     * @param string|null $body
     */
    public function __construct(?string $headers, ?string $body)
    {
        $this->headers = trim($headers);
        $this->body = trim($body);
    }

    /**
     * @return string|null
     */
    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    /**
     * @param bool $getDecoded
     * @return string|array|null
     */
    public function getBody(bool $getDecoded = false): string|array|null
    {
        if(!$this->body) {
            return null;
        } elseif($getDecoded) {
            $this->bodyDecoded ??= $this->decodeBody($this->body);
            return $this->bodyDecoded['SOAP-Body']['trainRouteResponse']['return'] ?? null;
        } else {
            return $this->body;
        }
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        $this->getBody(true);
        return $this->bodyDecoded['SOAP-Body']['SOAP-Fault']['faultstring'] ?? null;
    }

    /**
     * @param string $xml
     * @return array|null
     */
    private function decodeBody(string $xml): array|null
    {
        // Remove namespaces from XML
        $xml = preg_replace('/\s*xmlns[^=]*="[^"]*"/i', '', $xml);
        $xml = preg_replace('/[a-zA-Z\d]+:([a-zA-Z\d]+[=>\s])/', '$1', $xml);

        // Off XML parsing errors
        libxml_use_internal_errors(true);

        $xml = htmlspecialchars_decode($xml, ENT_XML1);
        $xml = str_replace('&', '&amp;', $xml);
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOWARNING);

        // Convert object to array
        $array = json_decode(json_encode($xml), true);

        return $array;
    }
}