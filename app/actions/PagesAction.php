<?php

namespace app\actions;

class PagesAction extends AbstractAction
{
    /**
     * @return never
     */
    public function run(): never
    {
        $pages = [
            'error404' => [404, $_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', 'Страница не найдена'],
            'error405' => [405, $_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed', null],
            'error400' => [400, $_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', 'Ошибка'],
            'error419' => [419, $_SERVER['SERVER_PROTOCOL'] . ' 419 Authentication Timeout', null],
            'error500' => [500, $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', 'Непредвиденная ошибка'],
        ];

        $page = $this->getVar('page');
        $page = isset($pages[$page]) ? $page : 'error404';
        [$code, $header, $title] = $pages[$page];

        header($header, true, $code);
        if($code == 400) {
            $error = $this->getVar('error') ?: 'Произошла непредвиденная ошибка';
            header('X-Error-Text: ' . rawurlencode($error));
        }

        $this->outputView(
            'pages/' . $page,
            compact('code', 'header', 'title')
                + (isset($error) ? compact('error') : [])
        );
    }
}