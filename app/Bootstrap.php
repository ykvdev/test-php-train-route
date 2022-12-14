<?php

namespace app;

use app\actions\PagesAction;
use app\actions\UserErrorException;
use app\services\ConfigService;
use app\services\LoggerService;
use DI\Container;
use FastRoute\Dispatcher;
use app\services\FastRouteService;
use app\services\WhoopsService;

class Bootstrap
{
    /** @var Container */
    private Container $di;

    /** @var ConfigService|mixed */
    private ConfigService $config;

    /** @var FastRouteService|mixed */
    private FastRouteService $fastRoute;

    /** @var LoggerService */
    private LoggerService $errorsLogger;

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        $this->di = new Container();
        $this->di->make(WhoopsService::class);
        $this->config = $this->di->get(ConfigService::class);
        $this->fastRoute = $this->di->get(FastRouteService::class);
        $this->errorsLogger = $this->di->make(LoggerService::class, ['logName' => $this->config->get('app_errors_log_name')]);
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function run(): void
    {
        $requestFilePath = $this->config->get('public_dir_path') . $_SERVER['REQUEST_URI'];
        if($fileMimeType = $this->getPublicFileMimeType($requestFilePath)) {
            header('Content-Type: ' . $fileMimeType);
            echo file_get_contents($requestFilePath);
        } else {
            $this->runAction();
        }
    }

    /**
     * Returns file MIME-type or false if not available for public
     *
     * @param string $filePath
     * @return string|false
     */
    private function getPublicFileMimeType(string $filePath): string|false
    {
        if(!is_file($filePath) || !is_readable($filePath)) {
            return false;
        }

        $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileMimeType = $this->config->get('available_public_file_extensions')[$fileExt] ?? null;
        if(!$fileMimeType) {
            return false;
        }

        $publicFileList = iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->config->get('public_dir_path'),
            \FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::SKIP_DOTS)));
        if(!isset($publicFileList[$filePath])) {
            return false;
        }

        return $fileMimeType;
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function runAction(): void
    {
        $routeData = $this->fastRoute->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        switch ($routeData['result']) {
            case Dispatcher::METHOD_NOT_ALLOWED:
                $action = PagesAction::class;
                $params = ['page' => 'error405'];
                break;

            case Dispatcher::FOUND:
                $action = $routeData['handler'];
                $params = $routeData['params'];
                break;

            default:
                $action = PagesAction::class;
                $params = ['page' => 'error404'];
        }

        try {
            $this->di->call([$action, 'run'], ['routeParams' => $params]);
        } catch (\Throwable $e) {
            $this->errorsLogger->error(convertExceptionToString($e));

            if(($params['page'] ?? null) == 'error500') {
                throw $e;
            } else {
                $this->di->call([PagesAction::class, 'run'], ['routeParams' => [
                    'page' => 'error500',
                    'error' => $e instanceof UserErrorException ? $e->getMessage() : 'Произошла внутренняя ошибка'
                ]]);
            }
        }
    }
};