<?php

namespace app\api\Starliner\Operations;

/**
 * Операция отдающая маршрут поезда
 */
class TrainRouteOperation implements OperationInterface
{
    /** @var array */
    private array $params;

    /**
     * @param string $trainNumber Номер поезда, примеры: 016А, 020У (из Москвы), 019У 037А (из Питера)
     * @param \DateTime $departureDate Дата отправления
     * @param string $departureStation Станция отправления, пример: Санкт-Петербург
     * @param string $arrivalStation Станция прибытия, пример: Москва
     */
    public function __construct(string $trainNumber, \DateTime $departureDate, string $departureStation, string $arrivalStation)
    {
        $this->params['train'] = $trainNumber;

        $this->params['travel_info'] = [
            'from' => $departureStation,
            'to' => $arrivalStation,
            'day' => (int)$departureDate->format('d'),
            'month' => (int)$departureDate->format('m'),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'trainRoute';
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}