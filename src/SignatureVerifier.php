<?php

namespace Matasar\Euspe;

use Matasar\Euspe\Dto\CertInfo;
use Matasar\Euspe\Dto\SignInfo;
use Matasar\Euspe\Enum\Error;
use Matasar\Euspe\Exception\VerificationException;
use Matasar\Euspe\Handler\ErrorHandler;

class SignatureVerifier
{
    private EusignSession $session;

    public function __construct(EusignSession $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $signature raw binary signature
     * @param string $hash raw binary hash of the signed data
     *
     * @throws VerificationException
     */
    public function verifyHash(string $signature, string $hash): SignInfo
    {
        $this->session->open();

        $e = new ErrorHandler(VerificationException::class, Error::UNKNOWN);
        $signTime = null;
        $useTSP = false;
        $c = CertInfo::emptyFields();

        $e->assert(
            euspe_verifyhashsign(
                $hash,
                $signature,
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
            'Failed to verify sign'
        );

        return new SignInfo($signTime, $useTSP, null, CertInfo::fromArray($c));
    }
}
