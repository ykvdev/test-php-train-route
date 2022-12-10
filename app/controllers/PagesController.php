<?php

namespace app\controllers;

class PagesController extends AbstractController
{
    protected function indexAction() : void
    {
        $this->renderView('pages/' . $this->getVar('view'));
    }

    public function error404Action(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
        $this->renderView('common/error404');
    }

    public function error405Action(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed', true, 405);
        $this->renderView('common/error405');
    }

    public function error419Action(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 419 Authentication Timeout', true, 419);
        $this->renderView('common/error419');
    }
}