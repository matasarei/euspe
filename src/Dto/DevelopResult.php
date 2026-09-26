<?php

namespace Matasar\Euspe\Dto;

/**
 * Properties are read-only by convention.
 */
class DevelopResult
{
    public EnvelopInfo $envelopInfo;
    public SignInfo $signInfo;

    public function __construct(EnvelopInfo $envelopInfo, SignInfo $signInfo)
    {
        $this->envelopInfo = $envelopInfo;
        $this->signInfo = $signInfo;
    }
}
