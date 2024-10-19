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

    public function assert(int $resultCode, string $title = ''): void
    {
        if ($resultCode === 0) {
            $this->reset();

            return;
        }

        $errorCode = dechex($this->errorCode ?: $resultCode);
        $errorMessage = '';
        euspe_geterrdescr($errorCode, $errorMessage);

        $errorMessage = sprintf(
            'ERR %d: %s',
            $errorCode,
            $errorMessage
        );

        if (strlen($title) > 0) {
            $errorMessage = $title . '; ' . $errorMessage;
        }

        $exception = new $this->exceptionClass($errorMessage, $errorCode);

        throw $exception;
    }

    public function reset()
    {
        $this->errorCode = $this->errorDefault;
    }
}
