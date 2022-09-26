<?php

namespace AkDevTodo\Backend\Helpers;

use \AkDevTodo\Backend\Defines\Request;

class Url
{
    private string $uri;
    private ?array $queryParams;

    public function __construct()
    {
        $this->uri = $this->initBasePath();

        $this->queryParams = match ($this->queryMethod()) {
            Request::GET => $this->initGetParams(),
            default => $this->initBodyParams()
        };
    }

    /**
     * @return string
     */
    private function initBasePath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'];
    }

    /**
     * @return array|null
     */
    private function initGetParams(): ?array
    {
        $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

        return $params ? $this->urlQueryToArray($params) : null;
    }


    /**
     * @return string|null
     */
    private function initBodyParams(): ?array
    {
        $params = file_get_contents('php://input', 'r');

        return $params ? $this->urlQueryToArray($params) : null;
    }

    /**
     * @param string $params
     * @return array
     */
    private function urlQueryToArray(string $params): array
    {
        parse_str($params, $paramsAsArray);

        return $paramsAsArray;
    }


    public function queryMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->uri;
    }

    /**
     * @return array|null
     */
    public function queryParams(): ?array
    {
        return $this->queryParams;
    }
}