<?php

namespace Core;

use Core\Traits\HttpMethods;
use Enums\HTTP;
use ReflectionMethod;

class Router
{
    use HttpMethods;

    protected static Router|null $instance = null;
    protected array $routes = [], $params = [];
    protected string $currentRoute;

    protected array $convertTypes = [
        'd' => 'int',
        '.' => 'string'
    ];

    static public function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    public function __call($name, array $args)
    {
        $methodName = 'set' . ucfirst($name);

        if (!method_exists($this, $methodName)) {
            throw new \Exception("Method [$methodName] does not exists in Router class");
        }

        $method = new ReflectionMethod($this::class, $methodName);

        if ($method->getReturnType() !== 'void') {
            return call_user_func_array([$this, $methodName], $args);
        }

        call_user_func_array([$this, $methodName], $args);
    }

    protected function setController(string $controller): static
    {
        $this->routes[$this->currentRoute]['controller'] = $controller;
        return $this;
    }

    protected function setAction(string $action): void
    {
        $this->routes[$this->currentRoute]['action'] = $action;
    }

    protected function setMethod(HTTP $method): static
    {
        $this->routes[$this->currentRoute]['method'] = $method->value;
        return $this;
    }

    protected function removeQueryVariables(string $uri): string
    {
        return preg_replace('/([\w\/\-]+)\?([\w\/%*&\?\=]+)/', '$1', $uri);
    }

    protected function match(string $uri): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $uri, $matches)) {
                $this->params = $this->buildParams($route, $matches, $params);
                return true;
            }
        }

        throw new \Exception("Route [$uri] not found", 404);
    }

    protected function buildParams(string $route, array $matches, array $params): array
    {
        preg_match_all('/\(\?P<[\w]+>(\\\\)?([\w\.][\+]*)\)/', $route, $types);
        $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (!empty($types)) {
            $lastKey = array_key_last($types);
            $step = 0;
            $types[$lastKey] = array_map(fn($value) => str_replace('+', '', $value), $types[$lastKey]);

            foreach($matches as $name => $value) {
                settype($value, $this->convertTypes[$types[$lastKey][$step]]);
                $params[$name] = $value;
                $step++;
            }
        }

        return $params;
    }

    static protected function setUri(string $uri): static
    {
        $uri = preg_replace('/\//', '\\/', $uri);
        $uri = preg_replace('/\{([a-z_]+):([^}]+)}/', '(?P<$1>$2)', $uri);
        $uri = "/^$uri$/i";

        $router = static::getInstance();

        $router->routes[$uri] = [];
        $router->currentRoute = $uri;

        return $router;
    }

    static public function dispatch(string $uri): string
    {
        $router = static::getInstance();
        $uri = $router->removeQueryVariables($uri);
        $uri = trim($uri, '/');

        if ($router->match($uri)) {
            $router->checkRequestMethod();

            $controller = $router->getController();
            $action = $router->getAction($controller);

            if ($controller->before($action, $router->params)) {
                $response = call_user_func_array([$controller, $action], $router->params);
                $controller->after($action);
            }
        }

        return \Core\json_response($response['code'], [
            'data' => $response['body'],
            'errors' => $response['errors']
        ]);
    }

    protected function checkRequestMethod()
    {
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

        if ($requestMethod !== strtolower($this->params['method'])) {
            throw new \Exception("Method [$requestMethod] is not allowed for this route!", 405);
        }

        unset($this->params['method']);
    }

    protected function getController(): Controller
    {
        $controller = $this->params['controller'] ?? null;

        if (!class_exists($controller)) {
            throw new \Exception("Controller [$controller] doesn't exists!");
        }

        unset($this->params['controller']);

        return new $controller;
    }

    protected function getAction(Controller $controller): string
    {
        $action = $this->params['action'] ?? null;

        if (!method_exists($controller, $action)) {
            throw new \Exception("Action [$action] doesn't exists in [".$controller::class."]!");
        }

        unset($this->params['action']);

        return $action;
    }
}
