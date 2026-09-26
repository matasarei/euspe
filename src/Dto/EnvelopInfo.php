<?php

namespace Matasar\Euspe\Dto;

/**
 * Properties are read-only by convention.
 */
class EnvelopInfo
{
    /** Signature time, MM.DD.YYYY HH:ii:ss */
    public ?string $signTime;
    public bool $useTSP;
    /** Decrypted data */
    public ?string $data;
    public CertInfo $senderInfo;

    public function __construct(?string $signTime, bool $useTSP, ?string $data, CertInfo $senderInfo)
    {
        $this->signTime = $signTime;
        $this->useTSP = $useTSP;
        $this->data = $data;
        $this->senderInfo = $senderInfo;
    }
}
