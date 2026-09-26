<?php

namespace Matasar\Euspe\Dto;

/**
 * Properties are read-only by convention.
 */
class SignInfo
{
    /** Signature time, MM.DD.YYYY HH:ii:ss */
    public ?string $signTime;
    public bool $useTSP;
    public ?string $data;
    public CertInfo $signerInfo;

    public function __construct(?string $signTime, bool $useTSP, ?string $data, CertInfo $signerInfo)
    {
        $this->signTime = $signTime;
        $this->useTSP = $useTSP;
        $this->data = $data;
        $this->signerInfo = $signerInfo;
    }
}
