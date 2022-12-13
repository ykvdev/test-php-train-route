<?php

use app\api\Starliner\Credentials;

require __DIR__ . '/../../../vendor/autoload.php';

$starliner = new \app\api\Starliner\Starliner(new Credentials(
    'https://test-api.starliner.ru/Api/connect/Soap/Train/1.1.0?wsdl',
    'test',
    'bYKoDO2it',
    'htk_test',
    22400
));

$results = $starliner->getTrainRoute()->setParams(
    '016А',
    date_create('2022-12-20'),
    'МОСКВА ОКТ',
    'МУРМАНСК'
)->exec();

echo implode(PHP_EOL . PHP_EOL, [
    '### REQUEST ###',
    'Headers: ' . $results->getRequest()->getHeaders(),
    'Body: ' . $results->getRequest()->getBody(),
]) . PHP_EOL . PHP_EOL;

echo implode(PHP_EOL . PHP_EOL, [
    '### RESPONSE ###',
    'Headers: ' . $results->getResponse()->getHeaders(),
    'Error: ' . $results->getResponse()->getError(),
    'Body: ' . $results->getResponse()->getBody(),
    'Body decoded: ' . print_r($results->getResponse()->getBody(true), true)
]) . PHP_EOL . PHP_EOL;