<?php

namespace app\controllers;

class IndexController extends AbstractController
{
    public function indexAction(): void
    {
        $this->renderView('index/index', [
            'someVar' => 123,

            'js' => ['/assets/js/page-index.js'],
            'css' => ['/assets/css/page-index.css'],
        ]);
    }
}