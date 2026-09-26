<?php

namespace Matasar\Euspe;

use Matasar\Euspe\Enum\Encoding;
use Matasar\Euspe\Exception\InitializationException;
use Matasar\Euspe\Handler\ErrorHandler;

/**
 * The EUSign library is process-wide: it is initialised once per process, however many sessions and
 * services are built, and finalised only by an explicit close(). Nothing is finalised on destruct.
 */
final class EusignSession
{
    private static ?int $openCharset = null;

    private int $charset;

    public function __construct(int $charset = Encoding::UTF8)
    {
        $this->charset = $charset;
    }

    /**
     * @throws InitializationException
     */
    public function open(): void
    {
        if (self::$openCharset !== null) {
            if (self::$openCharset !== $this->charset) {
                throw new InitializationException(sprintf(
                    'The library is already open with charset %d, not %d',
                    self::$openCharset,
                    $this->charset
                ));
            }

            return;
        }

        $e = new ErrorHandler(InitializationException::class);
        $e->assert(euspe_setcharset($this->charset), 'Failed to set charset');
        $e->assert(euspe_init($e->errorCode), 'Failed to initialize cryptographic library');

        self::$openCharset = $this->charset;
    }

    /**
     * @throws InitializationException
     */
    public function close(): void
    {
        if (self::$openCharset === null) {
            return;
        }

        self::$openCharset = null;

        $e = new ErrorHandler(InitializationException::class);
        $e->assert(euspe_finalize(), 'Failed to finalize cryptographic library');
    }
}
