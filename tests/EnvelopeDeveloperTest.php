<?php

use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\EnvelopeDeveloper;
use Matasar\Euspe\EusignSession;
use Matasar\Euspe\Exception\DecryptionException;
use PHPUnit\Framework\TestCase;

class EnvelopeDeveloperTest extends TestCase
{
    private EusignSession $session;
    private EnvelopeDeveloper $developer;

    protected function setUp(): void
    {
        require_once 'stubs.php';

        EuspeStub::reset();
        $this->session = new EusignSession();
        $this->session->open();
        $this->developer = new EnvelopeDeveloper($this->session);
        EuspeStub::reset();
    }

    protected function tearDown(): void
    {
        $this->session->close();
    }

    public function testDevelopOpensTheSession(): void
    {
        $this->session->close();
        EuspeStub::reset();

        $this->developer->develop(new PrivateKey('key', 'password'), 'envelope');

        $this->assertSame(['euspe_setcharset', 'euspe_init'], array_slice(EuspeStub::calledFunctions(), 0, 2));
    }

    /**
     * 1.1 freed a context that was never created, with null, and died with a TypeError.
     */
    public function testContextCreateFailureFreesNothing(): void
    {
        EuspeStub::failOn('euspe_ctxcreate', 0x0006);

        $e = $this->developAndCatch();

        $this->assertInstanceOf(DecryptionException::class, $e);
        $this->assertSame('Failed to create context; ERR 0x0006: Dummy error description', $e->getMessage());
        $this->assertSame(['euspe_ctxcreate', 'euspe_geterrdescr'], EuspeStub::calledFunctions());
    }

    /**
     * 1.1 replaced the original exception with the one from freeing.
     */
    public function testFailureInsideDevelopFreesBothContextsAndRethrowsTheOriginal(): void
    {
        EuspeStub::failOn('euspe_ctxdevelopdata', 0x0025);
        EuspeStub::failOn('euspe_ctxfree', 0x0001);

        $e = $this->developAndCatch();

        $this->assertInstanceOf(DecryptionException::class, $e);
        $this->assertSame('Failed to develop data; ERR 0x0025: Dummy error description', $e->getMessage());
        $this->assertSame(0x25, $e->getCode());
        $this->assertFreed();
    }

    public function testFailingToFreeThePrivateKeyStillFreesTheContext(): void
    {
        EuspeStub::failOn('euspe_signverify', 0x0023);
        EuspeStub::failOn('euspe_ctxfreeprivatekey', 0x0001);

        $e = $this->developAndCatch();

        $this->assertSame('Failed to verify sign; ERR 0x0023: Dummy error description', $e->getMessage());
        $this->assertFreed();
    }

    public function testFailureToFreeAfterSuccessIsReported(): void
    {
        EuspeStub::failOn('euspe_ctxfree', 0x0001);

        $e = $this->developAndCatch();

        $this->assertInstanceOf(DecryptionException::class, $e);
        $this->assertSame('Failed to free context; ERR 0x0001: Dummy error description', $e->getMessage());
        $this->assertSame(1, $e->getCode());
        $this->assertFreed();
    }

    public function testFailureToFreeThePrivateKeyAfterSuccessIsReportedAfterFreeingTheContext(): void
    {
        EuspeStub::failOn('euspe_ctxfreeprivatekey', 0x0002);

        $e = $this->developAndCatch();

        $this->assertSame('Failed to free private key context; ERR 0x0002: Dummy error description', $e->getMessage());
        $this->assertFreed();
    }

    private function developAndCatch(): Throwable
    {
        try {
            $this->developer->develop(new PrivateKey('key', 'password'), 'envelope');
        } catch (Throwable $e) {
            return $e;
        }

        $this->fail('No exception thrown');
    }

    private function assertFreed(): void
    {
        $frees = array_values(array_filter(
            EuspeStub::$calls,
            fn (array $call) => in_array($call[0], ['euspe_ctxfreeprivatekey', 'euspe_ctxfree'], true)
        ));

        $this->assertSame(
            [['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']], ['euspe_ctxfree', ['dummycontext']]],
            $frees
        );
    }
}
