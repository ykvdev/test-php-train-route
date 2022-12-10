<?php

namespace app\services;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\cachedDispatcher;

class FastRouteService
{
    private ConfigService $config;

    private Dispatcher $dispatcher;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;

        $this->dispatcher = cachedDispatcher(function(RouteCollector $r) {
            foreach ($this->config->get('services.fast_route.routes') as $routeParts) {
                [$method, $route, $controller, $action] = $routeParts;
                $r->addRoute($method, $route, [$controller, $action]);
            }
        }, [
            'cacheFile' => $this->config->get('services.fast_route.cache_file'),
            'cacheDisabled' => true,
        ]);
    }

    public function dispatch(string $requestMethod, string $requestUri): array
    {
        $routeInfo = $this->dispatcher->dispatch($requestMethod, $this->prepareRequestUri($requestUri));

        return [
            'result' => $routeInfo[0],
            'handler' => $routeInfo[1] ?? null,
            'params' => $routeInfo[2] ?? [],
        ];
    }

    /**
     * Strip query string (?foo=bar) and decode URI
     */
    private function prepareRequestUri(string $requestUri): string
    {
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        $requestUri = rawurldecode($requestUri);

        return $requestUri;
    }
}