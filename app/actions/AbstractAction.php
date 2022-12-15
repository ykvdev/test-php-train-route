<?php

namespace app\actions;

use app\services\ConfigService;
use app\services\ViewRendererService;

abstract class AbstractAction
{
    /** @var ConfigService */
    protected readonly ConfigService $config;

    /** @var ViewRendererService */
    protected readonly ViewRendererService $viewRenderer;

    /** @var array */
    protected readonly array $routeParams;

    /**
     * @param ConfigService $config
     * @param ViewRendererService $viewRenderer
     * @param array $routeParams
     */
    public function __construct(ConfigService $config, ViewRendererService $viewRenderer, array $routeParams = [])
    {
        $this->config = $config;
        $this->viewRenderer = $viewRenderer;
        $this->routeParams = $routeParams;
    }

    /**
     * Main entrypoint into the action class
     *
     * @return never
     */
    abstract public function run(): never;

    /**
     * @return bool
     */
    protected function isAjaxRequest(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') == 'xmlhttprequest';
    }

    /**
     * @param string $varName
     * @return string|null
     */
    protected function getVar(string $varName): ?string
    {
        return trim($this->routeParams[$varName] ?? $_GET[$varName] ?? '') ?: null;
    }

    /**
     * @param string $varName
     * @return string|null
     */
    protected function postVar(string $varName): ?string
    {
        return trim($_POST[$varName] ?? '') ?: null;
    }

    /**
     * @param string $viewAlias
     * @param array $vars
     * @return never
     */
    protected function outputView(string $viewAlias, array $vars = []): never
    {
        echo $this->viewRenderer->render($viewAlias, $vars);
        exit;
    }

    /**
     * @param string|array $data
     * @return never
     * @throws \JsonException
     */
    protected function outputJson(string|array $data): never
    {
        header('Content-Type: application/json');
        if(is_string($data)) {
            echo $data;
        } elseif(is_array($data) || is_object($data)) {
            echo json_encode($data, JSON_THROW_ON_ERROR);
        } else {
            throw new \RuntimeException('Data type for JSON render is wrong');
        }
        exit;
    }

    /**
     * @return never
     */
    protected function goBack(): never
    {
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @param string $toUrl
     * @param int $code
     * @return never
     */
    protected function redirect(string $toUrl, int $code = 301): never
    {
        header('Location: ' . $toUrl, true, $code);
        exit;
    }
}