<?php

namespace AkDevTodo\Backend;

use AkDevTodo\Backend\Controllers\Controller;
use AkDevTodo\Backend\Exceptions\CustomException;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;
use AkDevTodo\Backend\Tools\Env;
use AkDevTodo\Backend\Tools\Response;
use AkDevTodo\Backend\Tools\Route;
use AkDevTodo\Backend\Tools\UriHelper;

class App
{
    private static ?self $instance = null;
    private Route $route;
    private Controller $controller;
    private UriHelper $uriHelper;


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
            ->setUriHelper(new UriHelper())
            ->setRoute(new Route($this->uriHelper))
            ->run();
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param Route $route
     * @return $this
     */
    private function setRoute(Route $route): App
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return UriHelper
     */
    public function getUriHelper(): UriHelper
    {
        return $this->uriHelper;
    }

    /**
     * @param UriHelper $uriHelper
     * @return $this
     */
    public function setUriHelper(UriHelper $uriHelper): App
    {
        $this->uriHelper = $uriHelper;

        return $this;
    }


    /**
     * @return App
     */
    private function setExceptionHandler(): App
    {
        set_exception_handler(function (CustomException $e) {
            http_response_code($e->getCode());

            $response = new Response();

            $response
                ->setSuccess(false)
                ->setMessage($e->getMessage());

            echo $response;
        });

        return $this;
    }

    /**
     * @return void
     * @throws IncorrectRouteException
     */
    private function run()
    {
        try {
            $reflectionClass = new \ReflectionClass($this->getRoute()->getControllerName());
            $controller = $reflectionClass->newInstance();
        } catch (\ReflectionException $e) {
            throw new IncorrectRouteException();
        }

        $method = $this->getRoute()->getMethod();

        [
            'id' => $id,
            'arguments' => $arguments
        ] = $this->getRoute()->getArguments();

        $args = $id === null ? [$arguments] : [$id, $arguments];

        /** @var Response $response */
        $response = call_user_func_array([$controller, $method], $args);

        echo $response;
    }
}