<?php

namespace AkDevTodo\Backend\Tools;

use AkDevTodo\Backend\App;
use \AkDevTodo\Backend\Defines\Request;
use AkDevTodo\Backend\Exceptions\IncorrectRouteException;
use AkDevTodo\Backend\Helpers\Arr;
use AkDevTodo\Backend\MiddleWare\AbstractMiddleWare;

class Router
{

    /**
     * @return void
     */
    public static function loadRoute(): void
    {
        include __DIR__ . '/../Config/routes.php';
    }

    /**
     * @return string
     */
    public static function requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function get(string $route, array $config): void
    {
        if (self::requestMethod() == Request::GET) {
            self::route($route, $config);
        }
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function post(string $route, array $config): void
    {
        if (self::requestMethod() == Request::POST) {
            self::route($route, $config);
        }
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function patch(string $route, array $config): void
    {
        if (self::requestMethod() == Request::PATCH) {
            self::route($route, $config);
        }
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function delete(string $route, array $config): void
    {
        if (self::requestMethod() == Request::DELETE) {
            self::route($route, $config);
        }
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function any(string $route, array $config): void
    {
        self::route($route, $config);
    }

    /**
     * @param string $route
     * @param array $config
     * @return void
     * @throws IncorrectRouteException
     */
    public static function route(string $route, array $config): void
    {
        $requestUrl = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $requestUrl = rtrim($requestUrl, '/');
        $requestUrl = strtok($requestUrl, '?');
        $routeParts = explode('/', $route);
        $requestUrlParts = explode('/', $requestUrl);
        array_shift($routeParts);
        array_shift($requestUrlParts);

        if ($routeParts[0] === '' && count($requestUrlParts) == 0) {
            throw new IncorrectRouteException();
        }
        if (count($routeParts) != count($requestUrlParts)) {
            return;
        }
        $parameters = [];
        for ($index = 0; $index < count($routeParts); $index++) {
            $routePart = $routeParts[$index];
            if (preg_match("/^[$]/", $routePart)) {
                $routePart = ltrim($routePart, '$');
                array_push($parameters, $requestUrlParts[$index]);
                $$routePart = $requestUrlParts[$index];
            } else if ($routeParts[$index] != $requestUrlParts[$index]) {
                return;
            }
        }

        $controller = Arr::get($config, 0);
        $action = Arr::get($config, 1);
        $middleWares = Arr::get($config, 2, []);

        foreach ($middleWares as $middleWareName) {
            try {
                $reflectionClass = new \ReflectionClass($middleWareName);
                $middleWare = $reflectionClass->newInstance();
            } catch (\ReflectionException $e) {
                throw new IncorrectRouteException();
            }

            /** @var $middleWare AbstractMiddleWare */
            $middleWare->handle();
        }

        App::getInstance()->run($controller, $action, $parameters);
    }
}