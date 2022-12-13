<?php

namespace app\actions;

use app\services\ConfigService;
use app\services\ViewRendererService;
use DI\Container;

abstract class AbstractAction
{
    /** @var Container */
    protected readonly Container $di;

    /** @var ConfigService */
    protected readonly ConfigService $config;

    /** @var ViewRendererService */
    protected readonly ViewRendererService $viewRenderer;

    /** @var array */
    protected readonly array $routeParams;

    /**
     * @param Container $di
     * @param ConfigService $config
     * @param ViewRendererService $viewRenderer
     * @param array $routeParams
     */
    public function __construct(Container $di, ConfigService $config, ViewRendererService $viewRenderer, array $routeParams = [])
    {
        $this->di = $di;
        $this->config = $config;
        $this->viewRenderer = $viewRenderer;
        $this->routeParams = $routeParams;
    }

    /**
     * Main entrypoint into the action class
     *
     * @return void
     */
    abstract public function run(): void;

    /**
     * @return bool
     */
    protected function isAjaxRequest(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') == 'xmlhttprequest';
    }

    /**
     * @param string $var
     * @return string|null
     */
    protected function getVar(string $var): ?string
    {
        return trim($this->routeParams[$var] ?? $_GET[$var] ?? '') ?: null;
    }

    /**
     * @param string $var
     * @return string|null
     */
    protected function postVar(string $var): ?string
    {
        return trim($_POST[$var] ?? '') ?: null;
    }

    /**
     * @param string $viewAlias
     * @param array $vars
     * @return void
     */
    protected function renderView(string $viewAlias, array $vars = []): void
    {
        echo $this->viewRenderer->render($viewAlias, $vars);
    }

    /**
     * @param string|array $data
     * @return void
     * @throws \JsonException
     */
    protected function renderJson(string|array $data): void
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

    /**
     * @return void
     */
    protected function goBack(): void
    {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param string $toUrl
     * @param int $code
     * @return void
     */
    protected function redirect(string $toUrl, int $code = 301): void
    {
        header('Location: ' . $toUrl, true, $code);
        exit();
    }
}