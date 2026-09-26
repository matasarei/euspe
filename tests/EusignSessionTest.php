<?php

use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\EnvelopeDeveloper;
use Matasar\Euspe\Enum\Encoding;
use Matasar\Euspe\EusignSession;
use Matasar\Euspe\Exception\EuspeException;
use Matasar\Euspe\Exception\InitializationException;
use Matasar\Euspe\Hasher;
use Matasar\Euspe\SignatureVerifier;
use PHPUnit\Framework\TestCase;

class EusignSessionTest extends TestCase
{
    protected function setUp(): void
    {
        require_once 'stubs.php';

        (new EusignSession())->close();
        EuspeStub::reset();
    }

    protected function tearDown(): void
    {
        EuspeStub::reset();
        (new EusignSession())->close();
    }

    public function testInitRunsOncePerProcessHoweverManyServicesAreBuilt(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $session = new EusignSession();
            (new Hasher($session))->hashData('qwerty');
            (new SignatureVerifier($session))->verifyHash('signature', 'hash');
            (new EnvelopeDeveloper($session))->develop(new PrivateKey('key', 'password'), 'envelope');
            unset($session);
        }

        $calls = array_count_values(EuspeStub::calledFunctions());
        $this->assertSame(1, $calls['euspe_init']);
        $this->assertSame(1, $calls['euspe_setcharset']);
        $this->assertArrayNotHasKey('euspe_finalize', $calls);
    }

    public function testOpenIsIdempotent(): void
    {
        $session = new EusignSession();
        $session->open();
        $session->open();

        $this->assertSame([['euspe_setcharset', [Encoding::UTF8]], ['euspe_init', []]], EuspeStub::$calls);
    }

    public function testConstructionDoesNotInitialise(): void
    {
        $session = new EusignSession();
        new Hasher($session);

        $this->assertSame([], EuspeStub::$calls);
    }

    public function testCharsetIsPassedToTheLibrary(): void
    {
        (new EusignSession(Encoding::CP1251))->open();

        $this->assertSame([['euspe_setcharset', [Encoding::CP1251]], ['euspe_init', []]], EuspeStub::$calls);
    }

    public function testOpeningWithAnotherCharsetWhileOpenFails(): void
    {
        (new EusignSession(Encoding::UTF8))->open();

        $this->expectException(InitializationException::class);
        (new EusignSession(Encoding::CP1251))->open();
    }

    public function testDestructionDoesNotFinalise(): void
    {
        $session = new EusignSession();
        $session->open();
        unset($session);

        $this->assertNotContains('euspe_finalize', EuspeStub::calledFunctions());
    }

    public function testCloseFinalisesOnceAndOpenAfterCloseInitialisesAgain(): void
    {
        $session = new EusignSession();
        $session->open();
        $session->close();
        $session->close();
        $session->open();

        $this->assertSame(
            ['euspe_setcharset', 'euspe_init', 'euspe_finalize', 'euspe_setcharset', 'euspe_init'],
            EuspeStub::calledFunctions()
        );
    }

    public function testCloseWithoutOpenDoesNothing(): void
    {
        (new EusignSession())->close();

        $this->assertSame([], EuspeStub::$calls);
    }

    public function testInitFailureLeavesTheSessionClosed(): void
    {
        EuspeStub::failOn('euspe_init', 0x0004);
        $session = new EusignSession();

        try {
            $session->open();
            $this->fail('No exception thrown');
        } catch (InitializationException $e) {
            $this->assertSame('Failed to initialize cryptographic library; ERR 0x0004: Dummy error description', $e->getMessage());
            $this->assertSame(4, $e->getCode());
            $this->assertInstanceOf(EuspeException::class, $e);
        }

        unset(EuspeStub::$failures['euspe_init']);
        $session->open();

        $this->assertSame(2, array_count_values(EuspeStub::calledFunctions())['euspe_init']);
    }

    public function testSetCharsetFailure(): void
    {
        EuspeStub::failOn('euspe_setcharset', 0x0002);

        $this->expectException(InitializationException::class);
        $this->expectExceptionMessage('Failed to set charset; ERR 0x0002: Dummy error description');
        $this->expectExceptionCode(2);

        (new EusignSession())->open();
    }
}
