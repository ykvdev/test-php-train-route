<?php

namespace app\actions;

class PagesAction extends AbstractAction
{
    public function run(): void
    {
        $pages = [
            'error404' => [404, $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found'],
            'error405' => [405, $_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed'],
            'error419' => [419, $_SERVER['SERVER_PROTOCOL'] . ' 419 Authentication Timeout'],
            'error500' => [500, $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error'],
        ];

        $page = $this->getVar('page');
        [$code, $title] = $pages[$page] ?? [null, null];
        if(!$code) {
            $page = 'error404';
            [$code, $title] = $pages[$page] ?? [null, null];
        }

        if($code != 200) {
            header($title, true, $code);
        }
        if($code == 500 && $error = $this->getVar('error')) {
            header('Error-Text: ' . $error);
        }
        $this->renderView('pages/' . $page);
    }
}