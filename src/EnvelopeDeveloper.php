<?php

namespace Matasar\Euspe;

use Matasar\Euspe\Dto\CertInfo;
use Matasar\Euspe\Dto\DevelopResult;
use Matasar\Euspe\Dto\EnvelopInfo;
use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\Dto\SignInfo;
use Matasar\Euspe\Enum\Error;
use Matasar\Euspe\Exception\DecryptionException;
use Matasar\Euspe\Handler\ErrorHandler;

class EnvelopeDeveloper
{
    private EusignSession $session;

    public function __construct(EusignSession $session)
    {
        $this->session = $session;
    }

    /**
     * Decrypts an envelope with the recipient's private key and verifies the sender's signature inside it.
     *
     * @param string $envelope raw binary envelope
     * @param string|null $senderCert raw binary sender certificate, when the envelope does not carry it
     *
     * @throws DecryptionException
     */
    public function develop(PrivateKey $key, string $envelope, ?string $senderCert = null): DevelopResult
    {
        $this->session->open();

        $e = new ErrorHandler(DecryptionException::class, Error::UNKNOWN);
        $context = null;
        $keyContext = null;
        $completed = false;

        try {
            $e->assert(
                euspe_ctxcreate($context, $e->errorCode),
                'Failed to create context'
            );
            $e->assert(
                euspe_ctxreadprivatekeybinary(
                    $context,
                    $key->getContents(),
                    $key->getPassword(),
                    $keyContext,
                    $e->errorCode
                ),
                'Failed to read private key binary'
            );

            $envelopInfo = $this->developData($e, $keyContext, $envelope, $senderCert);
            $result = new DevelopResult($envelopInfo, $this->verifySignedData($e, $envelopInfo->data));
            $completed = true;

            return $result;
        } finally {
            $this->free($keyContext, $context, $completed);
        }
    }

    private function developData(ErrorHandler $e, string $keyContext, string $envelope, ?string $senderCert): EnvelopInfo
    {
        $data = null;
        $signTime = null;
        $useTSP = false;
        $c = CertInfo::emptyFields();

        $e->assert(
            euspe_ctxdevelopdata(
                $keyContext,
                $envelope,
                $senderCert,
                $data,
                $signTime,
                $useTSP,
                $c['issuer'],
                $c['issuerCN'],
                $c['serial'],
                $c['subject'],
                $c['subjCN'],
                $c['subjOrg'],
                $c['subjOrgUnit'],
                $c['subjTitle'],
                $c['subjState'],
                $c['subjLocality'],
                $c['subjFullName'],
                $c['subjAddress'],
                $c['subjPhone'],
                $c['subjEMail'],
                $c['subjDNS'],
                $c['subjEDRPOUCode'],
                $c['subjDRFOCode'],
                $e->errorCode
            ),
            'Failed to develop data'
        );

        return new EnvelopInfo($signTime, $useTSP, $data, CertInfo::fromArray($c));
    }

    private function verifySignedData(ErrorHandler $e, ?string $signedData): SignInfo
    {
        $data = null;
        $signTime = null;
        $useTSP = false;
        $c = CertInfo::emptyFields();

        $e->assert(
            euspe_signverify(
                $signedData,
                $signTime,
                $useTSP,
                $c['issuer'],
                $c['issuerCN'],
                $c['serial'],
                $c['subject'],
                $c['subjCN'],
                $c['subjOrg'],
                $c['subjOrgUnit'],
                $c['subjTitle'],
                $c['subjState'],
                $c['subjLocality'],
                $c['subjFullName'],
                $c['subjAddress'],
                $c['subjPhone'],
                $c['subjEMail'],
                $c['subjDNS'],
                $c['subjEDRPOUCode'],
                $c['subjDRFOCode'],
                $data,
                $e->errorCode
            ),
            'Failed to verify sign'
        );

        return new SignInfo($signTime, $useTSP, $data, CertInfo::fromArray($c));
    }

    /**
     * Frees whatever was created, both contexts even if the first one fails. A failure here is thrown only
     * when develop() itself succeeded; otherwise it would replace the exception that is already on its way.
     */
    private function free(?string $keyContext, ?string $context, bool $throwOnFailure): void
    {
        $e = new ErrorHandler(DecryptionException::class);
        $failure = null;

        if ($keyContext !== null) {
            try {
                $e->assert(euspe_ctxfreeprivatekey($keyContext), 'Failed to free private key context');
            } catch (DecryptionException $exception) {
                $failure = $exception;
            }
        }

        if ($context !== null) {
            try {
                $e->assert(euspe_ctxfree($context), 'Failed to free context');
            } catch (DecryptionException $exception) {
                $failure = $failure ?? $exception;
            }
        }

        if ($throwOnFailure && $failure !== null) {
            throw $failure;
        }
    }
}
