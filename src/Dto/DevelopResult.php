<?php

namespace Matasar\Euspe\Dto;

class DevelopResult
{
    public ?string $context = null;
    public ?string $privateKeyContext = null;
    public EnvelopInfo $envelopInfo;
    public SignInfo $signInfo;

    public function __construct(EnvelopInfo $envelopInfo, SignInfo $signInfo)
    {
        $this->envelopInfo = $envelopInfo;
        $this->signInfo = $signInfo;
    }
}
