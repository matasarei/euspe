<?php

namespace Matasar\Euspe\Handler;

use Matasar\Euspe\Enum\Error;

class ErrorHandler
{
    public int $errorCode;
    private int $errorDefault;
    private string $exceptionClass;

    /**
     * @param string $exceptionClass The class must support default arguments: string $message, int $code
     */
    public function __construct(string $exceptionClass, int $errorDefault = Error::NONE)
    {
        $this->exceptionClass = $exceptionClass;
        $this->errorDefault = $errorDefault;

        $this->reset();
    }

    public function assert(int $code, string $title = ''): void
    {
        if ($code === 0) {
            $this->reset();

            return;
        }

        $errorCode = dechex($code);
        $errorMessage = strlen($title) > 0 ? $title . ': ' : '';

        $exception = new $this->exceptionClass($errorMessage, $errorCode);

        throw $exception;
    }

    public function reset()
    {
        $this->errorCode = $this->errorDefault;
    }
}
