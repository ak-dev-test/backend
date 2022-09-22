<?php

namespace AkDevTodo\Backend\Tools;

use AkDevTodo\Backend\App;
use \AkDevTodo\Backend\Defines\Request;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;

class Route
{
    private string $controller;
    private string $method;
    private ?string $id;
    private ?array $arguments;


    public function __construct(UriHelper $uriHelper)
    {
        $url = $uriHelper->url();
        $splitedUrl = explode('/', $url);
        $filteredUrl = array_values(array_filter($splitedUrl, fn($item) => $item !== ''));

        if (count($filteredUrl) < 1 || count($filteredUrl) > 2) {
            throw new IncorrectRouteException();
        }

        $this->controller = $filteredUrl[0];
        $this->id = $filteredUrl[1] ?? null;
        $this->arguments = [
            'id' => $this->getId(),
            'arguments' => $uriHelper->queryParams()
        ];

        $this->method = match ($uriHelper->queryMethod()) {
            Request::GET => $this->id === null ? 'getAll' : 'getOne',
            Request::POST => 'create',
            Request::PATCH => 'update',
            Request::DELETE => 'delete',
        };
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        $appClass = new \ReflectionClass(App::class);
        $namespace = $appClass->getNamespaceName() . '\\Controllers\\';

        return $namespace . ucfirst(mb_strtolower($this->controller)) . 'Controller';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return array|null
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }
}