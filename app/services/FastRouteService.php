<?php

namespace app\services;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\cachedDispatcher;

class FastRouteService
{
    /** @var ConfigService */
    private ConfigService $config;

    /** @var Dispatcher */
    private Dispatcher $dispatcher;

    /**
     * @param ConfigService $config
     */
    public function __construct(ConfigService $config)
    {
        $this->config = $config;

        $this->dispatcher = cachedDispatcher(function(RouteCollector $r) {
            foreach ($this->config->get('services.fast_route.routes') as $routeParts) {
                //[$method, $route, $action] = $routeParts;
                $r->addRoute(...$routeParts);
            }
        }, [
            'cacheFile' => $this->config->get('services.fast_route.cache_file'),
            'cacheDisabled' => APP_ENV == APP_ENV_DEV,
        ]);
    }

    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @return array
     */
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
     * @param string $requestUri
     * @return string
     */
    private function prepareRequestUri(string $requestUri): string
    {
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        return rawurldecode($requestUri);
    }
}