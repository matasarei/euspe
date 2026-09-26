<?php

namespace Matasar\Euspe\Dto;

/**
 * Certificate owner and issuer, as the library reports them. Properties are read-only by convention.
 */
class CertInfo
{
    /**
     * Field names in the order the euspe_* functions return them.
     */
    public const FIELDS = [
        'issuer',
        'issuerCN',
        'serial',
        'subject',
        'subjCN',
        'subjOrg',
        'subjOrgUnit',
        'subjTitle',
        'subjState',
        'subjLocality',
        'subjFullName',
        'subjAddress',
        'subjPhone',
        'subjEMail',
        'subjDNS',
        'subjEDRPOUCode',
        'subjDRFOCode',
    ];

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

    /**
     * @param array<string, string> $fields keyed by the names in FIELDS; missing ones stay empty
     */
    public static function fromArray(array $fields): self
    {
        $info = new self();

        foreach (self::FIELDS as $field) {
            $info->$field = (string) ($fields[$field] ?? '');
        }

        return $info;
    }

    /**
     * @return array<string, string> every field, empty, ready to be filled by reference
     */
    public static function emptyFields(): array
    {
        return array_fill_keys(self::FIELDS, '');
    }
}
