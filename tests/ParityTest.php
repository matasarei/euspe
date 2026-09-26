<?php

use Matasar\Euspe\Dto\CertInfo;
use Matasar\Euspe\Dto\DevelopResult;
use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\Dto\SignInfo;
use Matasar\Euspe\EnvelopeDeveloper;
use Matasar\Euspe\EusignSession;
use Matasar\Euspe\Exception\DecryptionException;
use Matasar\Euspe\Exception\EncryptionException;
use Matasar\Euspe\Exception\VerificationException;
use Matasar\Euspe\Hasher;
use Matasar\Euspe\SignatureVerifier;
use PHPUnit\Framework\TestCase;

/**
 * The 1.1 behaviour frozen in acc90a9 (CryptoBehaviourTest), carried over to 2.0: the same euspe_* calls
 * in the same order, and the same values reaching the caller. Differences from 1.1 are only the listed
 * fixes, each covered by its own test:
 * - the library is initialised once per process and never finalised on destruct (EusignSessionTest);
 * - error codes stay ints and are shown as ERR 0x%04X (ErrorHandlerTest);
 * - contexts are freed in finally, and a failure while freeing never masks the original (EnvelopeDeveloperTest).
 */
class ParityTest extends TestCase
{
    private EusignSession $session;

    protected function setUp(): void
    {
        require_once 'stubs.php';

        EuspeStub::reset();
        $this->session = new EusignSession();
        $this->session->open();
        EuspeStub::reset();
    }

    protected function tearDown(): void
    {
        $this->session->close();
    }

    public function testHashData(): void
    {
        $this->assertSame('dummyhash', (new Hasher($this->session))->hashData('qwerty'));
        $this->assertSame([['euspe_hashdata', ['qwerty']]], EuspeStub::$calls);
    }

    public function testHashFile(): void
    {
        $this->assertSame('dummyhash', (new Hasher($this->session))->hashFile(__FILE__));
        $this->assertSame([['euspe_hashfile', [__FILE__]]], EuspeStub::$calls);
    }

    public function testHashDataFailure(): void
    {
        EuspeStub::failOn('euspe_hashdata', 0x0005);

        $this->assertThrows(
            fn () => (new Hasher($this->session))->hashData('qwerty'),
            EncryptionException::class,
            'Failed to hash data; ERR 0x0005: Dummy error description',
            5
        );
        $this->assertSame([['euspe_hashdata', ['qwerty']], ['euspe_geterrdescr', [5]]], EuspeStub::$calls);
    }

    public function testHashFileFailure(): void
    {
        EuspeStub::failOn('euspe_hashfile', 0x0005);

        $this->assertThrows(
            fn () => (new Hasher($this->session))->hashFile(__FILE__),
            EncryptionException::class,
            'Failed to hash file; ERR 0x0005: Dummy error description',
            5
        );
        $this->assertSame([['euspe_hashfile', [__FILE__]], ['euspe_geterrdescr', [5]]], EuspeStub::$calls);
    }

    public function testEmptyHash(): void
    {
        EuspeStub::$hash = '';

        $this->assertThrows(
            fn () => (new Hasher($this->session))->hashData('qwerty'),
            EncryptionException::class,
            'Hash generation failed',
            0xFFFF
        );
        $this->assertSame([['euspe_hashdata', ['qwerty']]], EuspeStub::$calls);
    }

    public function testVerifyHash(): void
    {
        $info = (new SignatureVerifier($this->session))->verifyHash('signature', 'hash');

        $this->assertInstanceOf(SignInfo::class, $info);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $info->signTime);
        $this->assertTrue($info->useTSP);
        $this->assertNull($info->data);
        $this->assertStubCertInfo($info->signerInfo);
        $this->assertSame([['euspe_verifyhashsign', ['hash', 'signature']]], EuspeStub::$calls);
    }

    public function testVerifyHashFailure(): void
    {
        EuspeStub::failOn('euspe_verifyhashsign', 0x0023);

        $this->assertThrows(
            fn () => (new SignatureVerifier($this->session))->verifyHash('signature', 'hash'),
            VerificationException::class,
            'Failed to verify sign; ERR 0x0023: Dummy error description',
            0x23
        );
        $this->assertSame(
            [['euspe_verifyhashsign', ['hash', 'signature']], ['euspe_geterrdescr', [0x23]]],
            EuspeStub::$calls
        );
    }

    public function testVerificationExceptionIsStillADecryptionException(): void
    {
        $this->assertInstanceOf(DecryptionException::class, new VerificationException());
    }

    public function testDevelop(): void
    {
        $result = (new EnvelopeDeveloper($this->session))->develop(new PrivateKey('key-bytes', 'secret'), 'envelope');

        $this->assertInstanceOf(DevelopResult::class, $result);
        $this->assertSame('dummydata', $result->envelopInfo->data);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $result->envelopInfo->signTime);
        $this->assertTrue($result->envelopInfo->useTSP);
        $this->assertStubCertInfo($result->envelopInfo->senderInfo);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $result->signInfo->signTime);
        $this->assertTrue($result->signInfo->useTSP);
        $this->assertNull($result->signInfo->data);
        $this->assertStubCertInfo($result->signInfo->signerInfo);
        $this->assertSame([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_signverify', ['dummydata']],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ], EuspeStub::$calls);
    }

    public function testDevelopPassesSenderCert(): void
    {
        (new EnvelopeDeveloper($this->session))->develop(new PrivateKey('key-bytes', 'secret'), 'envelope', 'sender-cert');

        $this->assertContains(
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', 'sender-cert']],
            EuspeStub::$calls
        );
    }

    public function testDevelopReadPrivateKeyFailure(): void
    {
        EuspeStub::failOn('euspe_ctxreadprivatekeybinary', 0x0018);

        $this->assertThrows(
            fn () => (new EnvelopeDeveloper($this->session))->develop(new PrivateKey('key-bytes', 'secret'), 'envelope'),
            DecryptionException::class,
            'Failed to read private key binary; ERR 0x0018: Dummy error description',
            0x18
        );
        $this->assertSame([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_geterrdescr', [0x18]],
            ['euspe_ctxfree', ['dummycontext']],
        ], EuspeStub::$calls);
    }

    public function testDevelopDataFailure(): void
    {
        EuspeStub::failOn('euspe_ctxdevelopdata', 0x0025);

        $this->assertThrows(
            fn () => (new EnvelopeDeveloper($this->session))->develop(new PrivateKey('key-bytes', 'secret'), 'envelope'),
            DecryptionException::class,
            'Failed to develop data; ERR 0x0025: Dummy error description',
            0x25
        );
        $this->assertSame([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_geterrdescr', [0x25]],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ], EuspeStub::$calls);
    }

    public function testDevelopSignVerifyFailure(): void
    {
        EuspeStub::failOn('euspe_signverify', 0x0023);

        $this->assertThrows(
            fn () => (new EnvelopeDeveloper($this->session))->develop(new PrivateKey('key-bytes', 'secret'), 'envelope'),
            DecryptionException::class,
            'Failed to verify sign; ERR 0x0023: Dummy error description',
            0x23
        );
        $this->assertSame([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_signverify', ['dummydata']],
            ['euspe_geterrdescr', [0x23]],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ], EuspeStub::$calls);
    }

    private function assertThrows(callable $operation, string $class, string $message, int $code): void
    {
        try {
            $operation();
            $this->fail('No exception thrown');
        } catch (Throwable $e) {
            $this->assertInstanceOf($class, $e);
            $this->assertSame($message, $e->getMessage());
            $this->assertSame($code, $e->getCode());
        }
    }

    private function assertStubCertInfo(CertInfo $info): void
    {
        $this->assertSame('"Fake CA". Qualified Trust Service Provider', $info->issuerCN);
        $this->assertSame('1234567890ABCDEF1234567890ABCDEF12345678', $info->serial);
        $this->assertSame('CN=John Doe;SN=Doe;GivenName=John;Serial=TINUA-1234567890;C=UA', $info->subject);
        $this->assertSame('John Doe', $info->subjCN);
        $this->assertSame('Fake Organization', $info->subjOrg);
        $this->assertSame('12345678', $info->subjEDRPOUCode);
        $this->assertSame('1234567890', $info->subjDRFOCode);
    }
}
