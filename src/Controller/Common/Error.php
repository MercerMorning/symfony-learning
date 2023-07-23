<?php


namespace App\Controller\Common;


class Error
{
    private string $propertyPath;
    private string $message;

    public function __construct(string $propertyPath, string $message) {
        $this->propertyPath = $propertyPath;
        $this->message = $message;
    }
}