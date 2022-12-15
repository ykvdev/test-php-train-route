<?php

namespace app\api\Starliner\Operations;

interface OperationInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getParams(): array;
}