<?php

/**
 * @param Throwable $e
 * @return string
 */
function convertExceptionToString(\Throwable $e): string
{
    return '(' . get_class($e) . ') ' . $e->getMessage()
        . PHP_EOL . $e->getFile() . ':' . $e->getLine()
        . PHP_EOL . $e->getTraceAsString();
}

/**
 * @param array $array
 * @param string $path
 * @return mixed
 */
function getArrayItemByPath(array $array, string $path): mixed
{
    $pathParts = explode('.', $path);
    foreach ($pathParts as $part) {
        $array = $array[$part] ?? null;
        if(!$array) {
            return false;
        }
    }

    return $array;
}