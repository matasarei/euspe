<?php

namespace Matasar\Euspe\Dto;

class CertInfo
{
    public string $issuer = '';
    public string $issuerCN = '';
    public string $serial = '';
    public string $subject = '';
    public string $subjCN = '';
    public string $subjOrg = '';
    public string $subjOrgUnit = '';
    public string $subjTitle = '';
    public string $subjState = '';
    public string $subjLocality = '';
    public string $subjFullName = '';
    public string $subjAddress = '';
    public string $subjPhone = '';
    public string $subjEMail = '';
    public string $subjDNS = '';
    public string $subjEDRPOUCode = '';
    public string $subjDRFOCode = '';
}
