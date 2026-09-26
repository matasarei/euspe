<?php

use Matasar\Euspe\Enum\Error;
use Matasar\Euspe\Handler\ErrorHandler;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        require_once 'stubs.php';

        EuspeStub::reset();
    }

    /**
     * @see euspe_geterrdescr stub
     */
    public function testAssertThrowsException(): void
    {
        $handler = new ErrorHandler(Exception::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Test error; ERR 0x0001: Dummy error description');
        $this->expectExceptionCode(Error::NOT_INITIALIZED);

        $callable = function (&$errorCode) {
            $errorCode = Error::NOT_INITIALIZED;

            return 1;
        };

        $handler->assert($callable($handler->errorCode), 'Test error');
    }

    /**
     * 1.1 ran the code through dechex(), so "ff" could not reach euspe_geterrdescr(int).
     */
    public function testCodeWithHexLettersKeepsItsValue(): void
    {
        $handler = new ErrorHandler(Exception::class);
        $handler->errorCode = 0xFF;

        try {
            $handler->assert(1, 'Test error');
            $this->fail('No exception thrown');
        } catch (Exception $e) {
            $this->assertStringContainsString('0x00FF', $e->getMessage());
            $this->assertSame(255, $e->getCode());
        }

        $this->assertSame([['euspe_geterrdescr', [255]]], EuspeStub::$calls);
    }

    /**
     * 1.1 reported 0x33 (51) as 33.
     */
    public function testCodeWithHexDigitsKeepsItsValue(): void
    {
        $handler = new ErrorHandler(Exception::class);
        $handler->errorCode = Error::CERT_NOT_FOUND;

        try {
            $handler->assert(1);
            $this->fail('No exception thrown');
        } catch (Exception $e) {
            $this->assertSame('ERR 0x0033: Dummy error description', $e->getMessage());
            $this->assertSame(51, $e->getCode());
        }
    }

    public function testResultCodeIsUsedWhenNoErrorCodeWasSet(): void
    {
        $handler = new ErrorHandler(Exception::class);

        $this->expectExceptionMessage('ERR 0x0002: Dummy error description');
        $this->expectExceptionCode(2);

        $handler->assert(2);
    }

    /**
     * The extension returns a code without setting the out-parameter when the library is not initialised;
     * 1.x and early 2.0 reported the untouched default instead, as "ERR 0xFFFF" with an empty description.
     */
    public function testResultCodeIsUsedWhenTheErrorCodeWasLeftAtItsDefault(): void
    {
        $handler = new ErrorHandler(Exception::class, Error::UNKNOWN);

        try {
            $handler->assert(Error::LIBRARY_LOAD, 'Test error');
            $this->fail('No exception thrown');
        } catch (Exception $e) {
            $this->assertSame('Test error; ERR 0x0003: Dummy error description', $e->getMessage());
            $this->assertSame(3, $e->getCode());
        }

        $this->assertSame([['euspe_geterrdescr', [3]]], EuspeStub::$calls);
    }

    public function testErrorCodeSetByTheExtensionWinsOverTheResultCode(): void
    {
        $handler = new ErrorHandler(Exception::class, Error::UNKNOWN);
        $handler->errorCode = Error::BAD_SIGNATURE;

        $this->expectExceptionMessage('ERR 0x0023: Dummy error description');
        $this->expectExceptionCode(0x23);

        $handler->assert(1);
    }

    public function testReset(): void
    {
        $handler = new ErrorHandler(Exception::class);
        $handler->assert(0, 'Test error');

        $this->assertEquals(Error::NONE, $handler->errorCode);
    }
}
