<?php

namespace AkDevTodo\Backend;

use AkDevTodo\Backend\Exceptions\CustomException;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\Helpers\Url;
use AkDevTodo\Backend\Tools\Env;
use AkDevTodo\Backend\Tools\Response;
use AkDevTodo\Backend\Tools\Router;

class App
{
    private static ?self $instance = null;
    private Url $urlHelper;

    private array $data = [];

    protected function __construct()
    {
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function __clone()
    {
        throw new \Exception('Unable to clone App singleton');
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize App singleton');
    }

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    public function init(): void
    {
        Env::load();

        $this
            ->setExceptionHandler()
            ->setUrlHelper(new Url());

        Router::loadRoute();
    }


    /**
     * @return Url
     */
    public function getUrlHelper(): Url
    {
        return $this->urlHelper;
    }

    /**
     * @param Url $urlHelper
     * @return $this
     */
    public function setUrlHelper(Url $urlHelper): App
    {
        $this->urlHelper = $urlHelper;

        return $this;
    }


    /**
     * @return App
     */
    private function setExceptionHandler(): App
    {
        if (filter_var(Env::get('DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
            return $this;
        }

        set_exception_handler(function (CustomException $e) {
            http_response_code($e->getCode());

            $response = new Response();

            $response
                ->setSuccess(false)
                ->setMessage($e->getMessage());

            echo $response;
            exit();
        });

        return $this;
    }

    /**
     * @param string $controllerName
     * @param string $action
     * @param array $parameters
     * @return void
     * @throws IncorrectRouteException
     */
    public function run(string $controllerName, string $action, array $parameters)
    {
        try {
            $reflectionClass = new \ReflectionClass($controllerName);
            $controller = $reflectionClass->newInstance();
        } catch (\ReflectionException $e) {
            throw new IncorrectRouteException();
        }

        $arguments = array_merge($parameters, [$this->getUrlHelper()->queryParams()]);

        /** @var Response $response */
        $response = call_user_func_array([$controller, $action], $arguments);

        echo $response;
        exit();
    }


    /**
     * @param string $key
     * @param $data
     * @return void
     */
    public function set(string $key, $data): void
    {
        $this->data[$key] = $data;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        return Arr::get($this->data, $key);

    }


}