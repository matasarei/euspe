<?php

use Matasar\Euspe\Crypto;
use Matasar\Euspe\Dto\CertInfo;
use Matasar\Euspe\Dto\DevelopResult;
use Matasar\Euspe\Dto\SignInfo;
use Matasar\Euspe\Enum\Encoding;
use Matasar\Euspe\Exception\DecryptionException;
use Matasar\Euspe\Exception\EncryptionException;
use Matasar\Euspe\Exception\InitializationException;
use PHPUnit\Framework\TestCase;

/**
 * Freezes the 1.1 behaviour of Crypto against the stubs: which euspe_* calls happen, in what order,
 * and what reaches the caller. Rows marked "1.1 bug" are the only ones 2.0 is allowed to change.
 */
class CryptoBehaviourTest extends TestCase
{
    private const LIFECYCLE_START = [
        ['euspe_setcharset', [Encoding::UTF8]],
        ['euspe_init', []],
    ];
    private const LIFECYCLE_END = [
        ['euspe_finalize', []],
    ];

    protected function setUp(): void
    {
        require_once 'stubs.php';

        EuspeStub::reset();
    }

    public function testHashData(): void
    {
        $crypto = new Crypto();
        $hash = $crypto->hash('qwerty', Crypto::HASH_DATA);
        unset($crypto);

        $this->assertSame('dummyhash', $hash);
        $this->assertCalls([['euspe_hashdata', ['qwerty']]]);
    }

    public function testHashFile(): void
    {
        $crypto = new Crypto();
        $hash = $crypto->hash(__FILE__, Crypto::HASH_FILE);
        unset($crypto);

        $this->assertSame('dummyhash', $hash);
        $this->assertCalls([['euspe_hashfile', [__FILE__]]]);
    }

    public function testHashDataFailure(): void
    {
        EuspeStub::failOn('euspe_hashdata', 0x0005);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->hash('qwerty', Crypto::HASH_DATA),
            EncryptionException::class,
            'Failed to hash data; ERR 5: Dummy error description',
            5
        );
        $this->assertCalls([['euspe_hashdata', ['qwerty']], ['euspe_geterrdescr', [5]]]);
    }

    public function testHashFileFailure(): void
    {
        EuspeStub::failOn('euspe_hashfile', 0x0005);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->hash(__FILE__, Crypto::HASH_FILE),
            EncryptionException::class,
            'Failed to hash file; ERR 5: Dummy error description',
            5
        );
        $this->assertCalls([['euspe_hashfile', [__FILE__]], ['euspe_geterrdescr', [5]]]);
    }

    public function testEmptyHash(): void
    {
        EuspeStub::$hash = '';

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->hash('qwerty', Crypto::HASH_DATA),
            EncryptionException::class,
            'Hash generation failed',
            0xFFFF
        );
        $this->assertCalls([['euspe_hashdata', ['qwerty']]]);
    }

    /**
     * 1.1 bug: the code goes through dechex() and back, so 0x33 (51) is reported and thrown as 33.
     */
    public function testErrorCodeWithHexDigitsIsMangled(): void
    {
        EuspeStub::failOn('euspe_hashdata', 0x0033);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->hash('qwerty', Crypto::HASH_DATA),
            EncryptionException::class,
            'Failed to hash data; ERR 33: Dummy error description',
            33
        );
        $this->assertCalls([['euspe_hashdata', ['qwerty']], ['euspe_geterrdescr', [33]]]);
    }

    /**
     * 1.1 bug: a code with a letter in hex ("ff") cannot reach euspe_geterrdescr(int) at all.
     */
    public function testErrorCodeWithHexLettersRaisesTypeError(): void
    {
        EuspeStub::failOn('euspe_hashdata', 0x00FF);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->hash('qwerty', Crypto::HASH_DATA),
            TypeError::class
        );
        $this->assertCalls([['euspe_hashdata', ['qwerty']]]);
    }

    public function testVerify(): void
    {
        $crypto = new Crypto();
        $info = $crypto->verify('signature', 'hash');
        unset($crypto);

        $this->assertInstanceOf(SignInfo::class, $info);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $info->signTime);
        $this->assertTrue($info->useTSP);
        $this->assertNull($info->data);
        $this->assertStubCertInfo($info->signerInfo);
        $this->assertCalls([['euspe_verifyhashsign', ['hash', 'signature']]]);
    }

    public function testVerifyFailure(): void
    {
        EuspeStub::failOn('euspe_verifyhashsign', 0x0023);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->verify('signature', 'hash'),
            DecryptionException::class,
            'Failed to verify sign; ERR 23: Dummy error description',
            23
        );
        $this->assertCalls([['euspe_verifyhashsign', ['hash', 'signature']], ['euspe_geterrdescr', [23]]]);
    }

    public function testDevelop(): void
    {
        $crypto = new Crypto();
        $result = $crypto->develop('key-bytes', 'secret', 'envelope');
        unset($crypto);

        $this->assertInstanceOf(DevelopResult::class, $result);
        $this->assertSame('dummydata', $result->envelopInfo->data);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $result->envelopInfo->signTime);
        $this->assertTrue($result->envelopInfo->useTSP);
        $this->assertStubCertInfo($result->envelopInfo->senderInfo);
        $this->assertMatchesRegularExpression('/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2}$/', $result->signInfo->signTime);
        $this->assertTrue($result->signInfo->useTSP);
        $this->assertNull($result->signInfo->data);
        $this->assertStubCertInfo($result->signInfo->signerInfo);
        $this->assertCalls([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_signverify', ['dummydata']],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ]);
    }

    public function testDevelopPassesSenderCert(): void
    {
        $crypto = new Crypto();
        $crypto->develop('key-bytes', 'secret', 'envelope', 'sender-cert');
        unset($crypto);

        $this->assertContains(
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', 'sender-cert']],
            EuspeStub::$calls
        );
    }

    /**
     * 1.1 bug: the context was never created, but it is freed anyway, with null.
     */
    public function testDevelopContextCreateFailure(): void
    {
        EuspeStub::failOn('euspe_ctxcreate', 0x0006);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->develop('key-bytes', 'secret', 'envelope'),
            TypeError::class
        );
        $this->assertCalls([['euspe_ctxcreate', []], ['euspe_geterrdescr', [6]]]);
    }

    public function testDevelopReadPrivateKeyFailure(): void
    {
        EuspeStub::failOn('euspe_ctxreadprivatekeybinary', 0x0018);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->develop('key-bytes', 'secret', 'envelope'),
            DecryptionException::class,
            'Failed to read private key binary; ERR 18: Dummy error description',
            18
        );
        $this->assertCalls([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_geterrdescr', [18]],
            ['euspe_ctxfree', ['dummycontext']],
        ]);
    }

    public function testDevelopDataFailure(): void
    {
        EuspeStub::failOn('euspe_ctxdevelopdata', 0x0025);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->develop('key-bytes', 'secret', 'envelope'),
            DecryptionException::class,
            'Failed to develop data; ERR 25: Dummy error description',
            25
        );
        $this->assertCalls([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_geterrdescr', [25]],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ]);
    }

    public function testDevelopSignVerifyFailure(): void
    {
        EuspeStub::failOn('euspe_signverify', 0x0023);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->develop('key-bytes', 'secret', 'envelope'),
            DecryptionException::class,
            'Failed to verify sign; ERR 23: Dummy error description',
            23
        );
        $this->assertCalls([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_signverify', ['dummydata']],
            ['euspe_geterrdescr', [23]],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ]);
    }

    /**
     * 1.1 bug: a failure while freeing replaces the original exception (here with a TypeError,
     * because the cleanup falls back to the 0xFFFF default code).
     */
    public function testDevelopFreeFailureMasksOriginal(): void
    {
        EuspeStub::failOn('euspe_ctxdevelopdata', 0x0025);
        EuspeStub::failOn('euspe_ctxfree', 0x0001);

        $this->assertThrows(
            fn (Crypto $crypto) => $crypto->develop('key-bytes', 'secret', 'envelope'),
            TypeError::class
        );
        $this->assertCalls([
            ['euspe_ctxcreate', []],
            ['euspe_ctxreadprivatekeybinary', ['dummycontext', 'key-bytes', 'secret']],
            ['euspe_ctxdevelopdata', ['dummyprivatekeycontext', 'envelope', null]],
            ['euspe_geterrdescr', [25]],
            ['euspe_ctxfreeprivatekey', ['dummyprivatekeycontext']],
            ['euspe_ctxfree', ['dummycontext']],
        ]);
    }

    public function testInitFailure(): void
    {
        EuspeStub::failOn('euspe_init', 0x0004);

        try {
            new Crypto();
            $this->fail('No exception thrown');
        } catch (InitializationException $e) {
            $this->assertSame('Failed to initialize cryptographic library; ERR 4: Dummy error description', $e->getMessage());
            $this->assertSame(4, $e->getCode());
        }

        $this->assertSame(
            [['euspe_setcharset', [Encoding::UTF8]], ['euspe_init', []], ['euspe_geterrdescr', [4]]],
            EuspeStub::$calls
        );
    }

    /**
     * 1.1 bug: every instance initialises the library again and finalises it on destruct.
     */
    public function testEveryInstanceInitialisesAndFinalises(): void
    {
        $first = new Crypto();
        $second = new Crypto();
        unset($first);
        $second->hash('qwerty', Crypto::HASH_DATA);
        unset($second);

        $this->assertSame(
            ['euspe_setcharset', 'euspe_init', 'euspe_setcharset', 'euspe_init', 'euspe_finalize', 'euspe_hashdata', 'euspe_finalize'],
            EuspeStub::calledFunctions()
        );
        $this->assertFalse(Crypto::$initialized);
    }

    /**
     * Runs $operation on a fresh Crypto, destroys it, and checks what was thrown.
     */
    private function assertThrows(callable $operation, string $class, ?string $message = null, ?int $code = null): void
    {
        $crypto = new Crypto();

        try {
            $operation($crypto);
            $this->fail('No exception thrown');
        } catch (Throwable $e) {
            $this->assertInstanceOf($class, $e);

            if ($message !== null) {
                $this->assertSame($message, $e->getMessage());
            }

            if ($code !== null) {
                $this->assertSame($code, $e->getCode());
            }
        } finally {
            unset($crypto, $e);
        }
    }

    /**
     * Checks the calls made between construction and destruction of one Crypto.
     */
    private function assertCalls(array $expected): void
    {
        $this->assertSame(array_merge(self::LIFECYCLE_START, $expected, self::LIFECYCLE_END), EuspeStub::$calls);
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
