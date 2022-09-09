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
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = "/^{$route}$/i";
        $this->routes[$route] = $params;

        $route = "/^{$route}$/i";
        $this->routes[$route] = $params;
//        d($params);
    }

    public function dispatch(string $url){
        $url = trim($url, '/');
        $url = $this->removeQueryStringVar($url);
        d($this->routes);
        if ($this->match($url)){

        }
    }

    protected function match(string $url)
    {
        foreach ($this->routes as $route => $params){
            if (preg_match($route, $url, $matches)){
               d($matches);
            }
        }
    }
    public function removeQueryStringVar(string $url){
        if ($url != ''){
            $parts = explode('?', $url, 2 );
            $url = $parts[0];
        }
        return $url;
      }

}

