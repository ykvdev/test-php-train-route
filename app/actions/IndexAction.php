<?php

namespace app\actions;

class IndexAction extends AbstractAction
{
    public function run(): void
    {
        if($this->isAjaxRequest()) {
            //$this->renderJson();
        } else {
            $this->renderView('index/index', [
                'js' => ['/assets/js/page-index.js'],
                'css' => ['/assets/css/page-index.css'],
            ]);
        }
    }
}