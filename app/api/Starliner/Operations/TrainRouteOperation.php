<?php

namespace app\api\Starliner\Operations;

use app\api\Starliner\Credentials;
use app\api\Starliner\Response;

/**
 * Метод отдающий маршрут поезда
 */
class TrainRouteOperation extends AbstractOperation
{
    /** @var array */
    private array $params;

    /**
     * @param \SoapClient $soapClient
     * @param Credentials $credentials
     */
    public function __construct(\SoapClient $soapClient, Credentials $credentials)
    {
        parent::__construct($soapClient, $credentials);

        $this->params['auth'] = [
            'login' => $credentials->getLogin(),
            'psw' => $credentials->getPassword(),
            'terminal' => $credentials->getTerminal(),
            'represent_id' => $credentials->getRepresentId(),
            'access_token' => '',
            'language' => '',
            'currency' => '',
        ];
    }

    /**
     * @param string $trainNumber Номер поезда, примеры: 016А, 020У (из Москвы), 019У 037А (из Питера)
     * @param \DateTime $departureDate Дата отправления
     * @param string $departureStation Станция отправления, пример: Санкт-Петербург
     * @param string $arrivalStation Станция прибытия, пример: Москва
     * @return $this
     */
    public function setParams(string $trainNumber, \DateTime $departureDate, string $departureStation, string $arrivalStation): self
    {
        $this->params['train'] = $trainNumber;

        $this->params['travel_info'] = [
            'from' => $departureStation,
            'to' => $arrivalStation,
            'day' => (int)$departureDate->format('d'),
            'month' => (int)$departureDate->format('m'),
        ];

        return $this;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'trainRoute';
    }

    /**
     * @return array
     */
    protected function getParams(): array
    {
        return $this->params;
    }
}