<?php

namespace Matasar\Euspe;

use Matasar\Euspe\Dto\DevelopResult;
use Matasar\Euspe\Dto\EnvelopInfo;
use Matasar\Euspe\Dto\SignInfo;
use Matasar\Euspe\Enum\Encoding;
use Matasar\Euspe\Enum\Error;
use Matasar\Euspe\Exception\DecryptionException;
use Matasar\Euspe\Exception\EncryptionException;
use Matasar\Euspe\Exception\InitializationException;
use Matasar\Euspe\Handler\ErrorHandler;
use Throwable;

class Crypto
{
    static bool $initialized = false;

    /**
     * @throws InitializationException
     */
    public function __construct()
    {
        if (!self::$initialized) {
            ini_set('allow_call_time_pass_reference', 'On');

            $e = new ErrorHandler(InitializationException::class);

            $e->assert(euspe_setcharset(Encoding::UTF8), 'Failed to set charset');
            $e->assert(euspe_init($e->errorCode), 'Failed to initialize cryptographic library');
        }
    }

    public function hash(string $data, bool $isFile): string
    {
        $e = new ErrorHandler(EncryptionException::class, Error::UNKNOWN);
        $hash = '';

        if ($isFile) {
            $e->assert(
                euspe_hashfile($data, $hash, $e->errorCode),
                'Failed to hash file'
            );
        } else {
            $e->assert(
                euspe_hashdata($data, $hash, $e->errorCode),
                'Failed to hash data'
            );
        }

        if (empty($hash)) {
            throw new EncryptionException('Hash generation failed', $e->errorCode);
        }

        return $hash;
    }

    public function develop(
        string $privateKey,
        string $privateKeyPassword,
        string $protectedData,
        string $senderCert = null
    ): DevelopResult {
        $e = new ErrorHandler(DecryptionException::class, Error::UNKNOWN);
        $r = new DevelopResult(new EnvelopInfo(), new SignInfo());

        try {
            $e->assert(
                euspe_ctxcreate($r->context, $e->errorCode),
                'Failed to create context'
            );
            $e->assert(
                euspe_ctxreadprivatekeybinary(
                    $r->context,
                    $privateKey,
                    $privateKeyPassword,
                    $r->privateKeyContext,
                    $e->errorCode
                ),
                'Failed to read private key binary'
            );
        } catch (Throwable $exception) {
            $e->assert(euspe_ctxfree($r->context), 'Failed to free context');

            throw $exception;
        }

        $envData = base64_decode($protectedData);
        $envSenderCert = $senderCert != null ? base64_decode($senderCert) : null;

        try {
            $e->assert(
                euspe_ctxdevelopdata(
                    $r->privateKeyContext,
                    $envData,
                    $envSenderCert,
                    $r->envelopInfo->data,
                    $r->envelopInfo->signTime,
                    $r->envelopInfo->useTSP,
                    $r->envelopInfo->senderInfo->issuer,
                    $r->envelopInfo->senderInfo->issuerCN,
                    $r->envelopInfo->senderInfo->serial,
                    $r->envelopInfo->senderInfo->subject,
                    $r->envelopInfo->senderInfo->subjCN,
                    $r->envelopInfo->senderInfo->subjOrg,
                    $r->envelopInfo->senderInfo->subjOrgUnit,
                    $r->envelopInfo->senderInfo->subjTitle,
                    $r->envelopInfo->senderInfo->subjState,
                    $r->envelopInfo->senderInfo->subjLocality,
                    $r->envelopInfo->senderInfo->subjFullName,
                    $r->envelopInfo->senderInfo->subjAddress,
                    $r->envelopInfo->senderInfo->subjPhone,
                    $r->envelopInfo->senderInfo->subjEMail,
                    $r->envelopInfo->senderInfo->subjDNS,
                    $r->envelopInfo->senderInfo->subjEDRPOUCode,
                    $r->envelopInfo->senderInfo->subjDRFOCode,
                    $e->errorCode
                ),
                'Failed to develop data'
            );
        } catch (Throwable $exception) {
            $e->assert(euspe_ctxfreeprivatekey($r->privateKeyContext), 'Failed to free private key context');
            $e->assert(euspe_ctxfree($r->context), 'Failed to free context');

            throw $exception;
        }

        try {
            $e->assert(
                euspe_signverify(
                    $r->envelopInfo->data,
                    $r->signInfo->signTime,
                    $r->signInfo->useTSP,
                    $r->signInfo->signerInfo->issuer,
                    $r->signInfo->signerInfo->issuerCN,
                    $r->signInfo->signerInfo->serial,
                    $r->signInfo->signerInfo->subject,
                    $r->signInfo->signerInfo->subjCN,
                    $r->signInfo->signerInfo->subjOrg,
                    $r->signInfo->signerInfo->subjOrgUnit,
                    $r->signInfo->signerInfo->subjTitle,
                    $r->signInfo->signerInfo->subjState,
                    $r->signInfo->signerInfo->subjLocality,
                    $r->signInfo->signerInfo->subjFullName,
                    $r->signInfo->signerInfo->subjAddress,
                    $r->signInfo->signerInfo->subjPhone,
                    $r->signInfo->signerInfo->subjEMail,
                    $r->signInfo->signerInfo->subjDNS,
                    $r->signInfo->signerInfo->subjEDRPOUCode,
                    $r->signInfo->signerInfo->subjDRFOCode,
                    $r->signInfo->data,
                    $e->errorCode
                ),
                'Failed to verify sign'
            );
        } catch (Throwable $exception) {
            $this->freeContext($e, $r);

            throw $exception;
        }

        $this->freeContext($e, $r);

        return $r;
    }

    private function freeContext(ErrorHandler $e, DevelopResult $r): void
    {
        $e->assert(euspe_ctxfreeprivatekey($r->privateKeyContext), 'Failed to free private key context');
        $e->assert(euspe_ctxfree($r->context), 'Failed to free context');
    }

    public function __destruct()
    {
        self::$initialized = false;

        $e = new ErrorHandler(InitializationException::class);
        $e->assert(euspe_finalize(), 'Failed to finalize cryptographic library');
    }
}
