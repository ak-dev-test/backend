<?php

namespace AkDevTodo\Backend;

use AkDevTodo\Backend\Controllers\Controller;
use AkDevTodo\Backend\Exceptions\CustomException;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;
use AkDevTodo\Backend\Tools\Env;
use AkDevTodo\Backend\Tools\Response;
use AkDevTodo\Backend\Tools\Router;
use AkDevTodo\Backend\Tools\UriHelper;

class App
{
    private static ?self $instance = null;
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
            ->setUriHelper(new UriHelper());

        Router::loadRoute();
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
        if (Env::get('DEBUG')) {
            return $this;
        }

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

        $arguments = array_merge($parameters, [$this->getUriHelper()->queryParams()]);

        /** @var Response $response */
        $response = call_user_func_array([$controller, $action], $arguments);

        echo $response;
    }
}