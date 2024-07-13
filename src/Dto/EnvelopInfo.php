<?php

namespace Matasar\Euspe\Dto;

class EnvelopInfo
{
    public ?string $signTime = null;
    public bool $useTSP = false;
    public ?string $data = null;
    public CertInfo $senderInfo;

    function __construct()
    {
        $this->senderInfo = new CertInfo();
    }
}
