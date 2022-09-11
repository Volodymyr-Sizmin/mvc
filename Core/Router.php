<?php

namespace Core;

class Router
{
    protected array $routes = [], $params = [];

    protected array $convertTypes = [
        'd' => 'int',
        'w' => 'string'
    ];

    public function add(string $route, array $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        $route = "/^{$route}$/i";
        $this->routes[$route] = $params;
        d("Params", $params);
    }

    public function dispatch(string $url)
    {
        $url = trim($url, '/');
        $url = $this->removeQueryStringVar($url);
        if ($this->match($url)) {
            d(" params controller", $this->params['controller']);
            if (array_key_exists('method', $this->params) && ($_SERVER['REQUEST_METHOD'] !== $this->params['method'])) {
                throw new \Exception("Method " . $_SERVER['REQUEST_METHOD'] . " doesn't supported by this route");
            }
            unset($this->params['method']);

            if (class_exists($this->params['controller'])) {
                $controller = $this->params['controller'];
                d('Controller', $this->params['controller']);
                unset($this->params['controller']);

                if (method_exists($controller, $this->params['action'])){
                    $controller = new $controller;
                    $action = $this->params['action'];
                    unset($this->params['action']);

                    if ($controller->before($action)) {
                        call_user_func_array([$controller, $action], $this->params);
                        $controller->after($action);
                    }
                    } else {
                    throw new \Exception("Action {$this->params['action']}  in class {$controller} not found");
                }
            } else {
                throw new \Exception("Controller class {$this->params['controller']} not found");
            }
        }  else {
            throw new \Exception('Route not found');
        }
    }

    protected function match(string $url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $this->setParams($route, $matches, $params);
                return true;
                }
            }
        return false;
    }

    protected function setParams(string $route, array $matches, array $params)
{
    preg_match_all('/\(?P<[\w]+>\\\\(\w[\+])\)/', $route, $types);
    d($matches);
    $matches = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
    d($matches);
    if (!empty($types)) {
        $step = 0;
        $lastkey = count($types) - 1;
        foreach ($matches as $key => $match) {
            $types[$lastkey] = str_replace('+', '', $types[$lastkey]);
            settype($match, $this->convertTypes[$types[$lastkey][$step]]);
            $params[$key] = $match;
            $step++;
            }
        }
    return $params;
}
    public function removeQueryStringVar(string $url){
        if ($url != ''){
            $parts = explode('?', $url, 2 );
            $url = $parts[0];
        }
        return $url;
      }

}

