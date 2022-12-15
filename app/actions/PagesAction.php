<?php

namespace app\actions;

class PagesAction extends AbstractAction
{
    /**
     * @return void
     */
    public function run(): void
    {
        $pages = [
            'error404' => [404, $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found'],
            'error405' => [405, $_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed'],
            'error419' => [419, $_SERVER['SERVER_PROTOCOL'] . ' 419 Authentication Timeout'],
            'error500' => [500, $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error'],
        ];

        $page = $this->getVar('page');
        $page = isset($pages[$page]) ? $pages[$page] : 'error404';
        [$code, $title] = $pages[$page];

        if($code != 200) {
            header($title, true, $code);
        } elseif($code == 500 && $error = $this->getVar('error')) {
            header('X-Error-Text: ' . $error);
        }
        $this->outputView(
            'pages/' . $page,
            isset($error) ? compact('error') : []
        );
    }
}