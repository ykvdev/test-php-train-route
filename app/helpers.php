<?php

function convertExceptionToString(\Throwable $e): string
{
    return '(' . get_class($e) . ') ' . $e->getMessage()
        . PHP_EOL . $e->getFile() . ':' . $e->getLine()
        . PHP_EOL . $e->getTraceAsString();
}