<?php
declare(strict_types=1);

namespace App\ExceptionHandler;

use Throwable;

interface ExceptionHandlerInterface
{
    public function handle(Throwable $throwable) :void;
}