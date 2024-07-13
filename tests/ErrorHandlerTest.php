<?php

use Matasar\Euspe\Handler\ErrorHandler;
use Matasar\Euspe\Enum\Error;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        require_once 'stubs.php';
    }

    /**
     * @see euspe_geterrdescr stub
     */
    public function testAssertThrowsException()
    {
        $handler = new ErrorHandler(Exception::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Test error: Dummy error description');

        $callable = function (&$errorCode) {
            $errorCode = Error::NOT_INITIALIZED;

            return 1;
        };

        $handler->assert($callable($handler->errorCode), 'Test error');

        $this->assertEquals(Error::NOT_INITIALIZED, $handler->errorCode);
    }

    public function testReset()
    {
        $handler = new ErrorHandler(Exception::class);
        $handler->assert(0, 'Test error');

        $this->assertEquals(Error::NONE, $handler->errorCode);
    }
}
