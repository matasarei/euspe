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

        // The extension leaves the out-parameter untouched for some failures, e.g. when not initialised
        $errorCode = $this->errorCode !== $this->errorDefault ? $this->errorCode : $resultCode;
        $errorMessage = '';
        euspe_geterrdescr($errorCode, $errorMessage);

        $errorMessage = sprintf(
            'ERR 0x%04X: %s',
            $errorCode,
            $errorMessage
        );

        if (strlen($title) > 0) {
            $errorMessage = $title . '; ' . $errorMessage;
        }

        throw new $this->exceptionClass($errorMessage, $errorCode);
    }

    public function reset(): void
    {
        $this->errorCode = $this->errorDefault;
    }
}
