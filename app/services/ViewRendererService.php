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
        $rendererConfig = $config->get('services.view_renderer');
        $this->renderer = new Engine($rendererConfig['views_dir'], $rendererConfig['views_ext']);
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
            throw new \RuntimeException("View \"{$viewAlias}\" not found");
        }

        return $view->render($vars);
    }
}