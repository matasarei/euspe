<?php

namespace Matasar\Euspe\Dto;

class SignInfo
{
    public ?string $signTime = null;
    public bool $useTSP = false;
    public ?string $data = null;
    public CertInfo $signerInfo;

    public function __construct()
    {
        $this->signerInfo = new CertInfo();
    }
}
