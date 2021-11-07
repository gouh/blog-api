<?php

namespace Gouh\BlogApi\Request;

class ServerRequest
{
    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var array
     */
    private array $queryParams;

    /**
     * @var array|null
     */
    private ?array $parsedBody;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var array
     */
    private array $serverParams;

    /**
     * @var array
     */
    private array $attributes;

    /**
     * @param string $method HTTP method
     * @param array<string, string|string[]> $headers Request headers
     * @param array|null $queryParams
     * @param array|null $parsedBody
     * @param string $version Protocol version
     * @param array $serverParams Typically the $_SERVER super global
     */
    public function __construct(
        string $method,
        array  $headers = [],
        array  $queryParams = [],
        array  $parsedBody = null,
        string $version = '1.1',
        array  $serverParams = []
    )
    {
        $this->setMethod($method);
        $this->setHeaders($headers);
        $this->setQueryParams($queryParams);
        $this->setParsedBody($parsedBody);
        $this->setVersion($version);
        $this->setServerParams($serverParams);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getQueryParams(): ?array
    {
        return $this->queryParams;
    }

    /**
     * @param array|null $queryParams
     */
    public function setQueryParams(?array $queryParams): void
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return array|null
     */
    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }

    /**
     * @param array|null $parsedBody
     */
    public function setParsedBody(?array $parsedBody): void
    {
        $this->parsedBody = $parsedBody;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * @param array $serverParams
     */
    public function setServerParams(array $serverParams): void
    {
        $this->serverParams = $serverParams;
    }

    /**
     * @param string $attributeId
     * @return string|null
     */
    public function getAttribute(string $attributeId): ?string
    {
        return $this->attributes[$attributeId] ?? null;
    }

    /**
     * @param string $attributeId
     * @param string $attributeValue
     * @return void
     */
    public function setAttribute(string $attributeId, string $attributeValue): void
    {
        $this->attributes[$attributeId] = $attributeValue;
    }

    /**
     * @param array $attributes
     * @return ServerRequest
     */
    public static function fromGlobals(array $attributes): ServerRequest
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $headers = getallheaders();
        $body = json_decode(file_get_contents("php://input"), true);
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
        $serverRequest = new ServerRequest($method, $headers, $_GET, $body, $protocol, $_SERVER);
        foreach ($attributes as $attributeId => $attributeValue){
            $serverRequest->setAttribute($attributeId, $attributeValue);
        }
        return $serverRequest;
    }
}
