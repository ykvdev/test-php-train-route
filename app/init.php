<?php

define('ROOT_PATH', realpath(__DIR__ . '/..'));

const APP_ENV_DEV = 'dev';
const APP_ENV_PROD = 'prod';
const APP_ENV = APP_ENV_DEV;

require ROOT_PATH . '/vendor/autoload.php';