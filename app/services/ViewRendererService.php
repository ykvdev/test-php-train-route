<?php

namespace app\services;

use League\Plates\Engine;

class ViewRendererService
{
    /** @var Engine */
    private Engine $renderer;

    /**
     * @param ConfigService $config
     */
    public function __construct(ConfigService $config)
    {
        $this->renderer = new Engine($config->get('services.view_renderer.views_dir'), $config->get('services.view_renderer.views_ext'));
    }

    /**
     * @param string $viewAlias
     * @param array $vars
     * @return string
     * @throws \Throwable
     */
    public function render(string $viewAlias, array $vars = []): string
    {
        $view = $this->renderer->make($viewAlias);
        if(!$view->exists()) {
            throw new \RuntimeException('View not found, alias: ' . $viewAlias);
        }

        return $view->render($vars);
    }
}