<?php

namespace app\actions;

use app\api\Starliner\ResponseException;
use app\services\StarlinerApiService;

class IndexAction extends AbstractAction
{
    /** @var string */
    private readonly string $trainNumber;

    /** @var \DateTime */
    private readonly \DateTime $departureDate;

    /** @var string */
    private readonly string $departureStation;

    /** @var string */
    private readonly string $arrivalStation;

    /**
     * @return void
     * @throws \JsonException
     */
    public function run(): void
    {
        if($this->isAjaxRequest()) {
            $this->initAndValidateInputData();
            $this->renderJson(['route' => $this->getTrainRoute()]);
        } else {
            $this->renderView('index/index', [
                'js' => ['/assets/js/page-index.js'],
                'css' => ['/assets/css/page-index.css'],
            ]);
        }
    }

    /**
     * @return void
     * @throws UserErrorException
     */
    private function initAndValidateInputData(): void
    {
        if(!$this->trainNumber = $this->postVar('trainNumber')) {
            throw new UserErrorException('Номер поезда обязателен для заполнения');
        } elseif(!preg_match('/^\d{3}[А-Я]$/u', $this->trainNumber)) {
            throw new UserErrorException('Номер поезда указан в неверном формате');
        }

        if(!$departureDate = $this->postVar('departureDate')) {
            throw new UserErrorException('Дата отправления обязательна для заполнения');
        } elseif(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $departureDate)) {
            throw new UserErrorException('Дата отправления указана в неверном формате');
        } elseif(($this->departureDate = date_create($departureDate)) < date_create()) {
            throw new UserErrorException('Дата отправления не может быть меньше текущей даты');
        }

        if(!$this->departureStation = $this->postVar('departureStation')) {
            throw new UserErrorException('Станция отправления обязательна для заполнения');
        } elseif(!preg_match('/^[а-яё\-\s.,]{3,}$/ui', $this->departureStation)) {
            throw new UserErrorException('Станция отправления указана в неверном формате');
        }

        if(!$this->arrivalStation = $this->postVar('arrivalStation')) {
            throw new UserErrorException('Станция прибытия обязательна для заполнения');
        } elseif(!preg_match('/^[а-яё\-\s.,]{3,}$/ui', $this->arrivalStation)) {
            throw new UserErrorException('Станция прибытия указана в неверном формате');
        }
    }

    /**
     * @return array
     * @throws UserErrorException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws ResponseException
     */
    private function getTrainRoute(): array
    {
        $starliner = $this->di->make(StarlinerApiService::class);
        $route = $starliner->execOperation($starliner->getTrainRouteOperation()->setParams(
            $this->trainNumber,
            $this->departureDate,
            $this->departureStation,
            $this->arrivalStation
        ))['route_list'] ?? null;

        if(!$route) {
            throw new UserErrorException('При получении маршрута произошла ошибка');
        }

        return $route;
    }
}