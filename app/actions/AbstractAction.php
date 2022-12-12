<?php

namespace app\actions;

use app\services\ConfigService;
use app\services\ViewRendererService;
use DI\Container;

abstract class AbstractAction
{
    protected Container $di;

    protected ConfigService $config;

    protected ViewRendererService $viewRenderer;

    protected array $routeParams;

    public function __construct(Container $di, ConfigService $config, ViewRendererService $viewRenderer, array $routeParams = [])
    {
        $this->di = $di;
        $this->config = $config;
        $this->viewRenderer = $viewRenderer;
        $this->routeParams = $routeParams;
    }

    /*
     * Main entrypoint into the action class
     */
    abstract public function run(): void;

    protected function isAjaxRequest(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') == 'xmlhttprequest';
    }

    protected function getVar(string $var): ?string
    {
        return trim($this->routeParams[$var] ?? $_GET[$var] ?? '') ?: null;
    }

    protected function postVar(string $var): ?string
    {
        return trim($_POST[$var] ?? '') ?: null;
    }

    protected function renderView(string $viewAlias, array $vars = []): void
    {
        echo $this->viewRenderer->render($viewAlias, $vars);
    }

    protected function renderJson(mixed $data): void
    {
        header('Content-Type: application/json');

        if(is_string($data)) {
            echo $data;
        } elseif(is_array($data) || is_object($data)) {
            echo json_encode($data, JSON_THROW_ON_ERROR);
        } else {
            throw new \RuntimeException('Data type for JSON render is wrong');
        }
    }

    protected function goBack(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    protected function redirect(string $toUrl, int $code = 301): void
    {
        header('Location: ' . $toUrl, true, $code);
        exit();
    }
}