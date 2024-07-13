<?php

namespace Matasar\Euspe\Enum;

class Error
{
    public const NONE = 0x0000;
    public const UNKNOWN = 0xFFFF;
    public const NOT_SUPPORTED = 0xFFFE;
    public const NOT_INITIALIZED = 0x0001;
    public const BAD_PARAMETER = 0x0002;
    public const LIBRARY_LOAD = 0x0003;
    public const READ_SETTINGS = 0x0004;
    public const TRANSMIT_REQUEST = 0x0005;
    public const MEMORY_ALLOCATION = 0x0006;
    public const WARNING_END_OF_ENUM = 0x0007;
    public const PROXY_NOT_AUTHORIZED = 0x0008;
    public const NO_GUI_DIALOGS = 0x0009;
    public const DOWNLOAD_FILE = 0x000A;
    public const WRITE_SETTINGS = 0x000B;
    public const CANCELED_BY_GUI = 0x000C;
    public const OFFLINE_MODE = 0x000D;
    public const KEY_MEDIAS_FAILED = 0x0011;
    public const KEY_MEDIAS_ACCESS_FAILED = 0x0012;
    public const KEY_MEDIAS_READ_FAILED = 0x0013;
    public const KEY_MEDIAS_WRITE_FAILED = 0x0014;
    public const WARNING_KEY_MEDIAS_READ_ONLY = 0x0015;
    public const KEY_MEDIAS_DELETE = 0x0016;
    public const KEY_MEDIAS_CLEAR = 0x0017;
    public const BAD_PRIVATE_KEY = 0x0018;
    public const PKI_FORMATS_FAILED = 0x0021;
    public const CSP_FAILED = 0x0022;
    public const BAD_SIGNATURE = 0x0023;
    public const AUTH_FAILED = 0x0024;
    public const NOT_RECEIVER = 0x0025;
    public const STORAGE_FAILED = 0x0031;
    public const BAD_CERT = 0x0032;
    public const CERT_NOT_FOUND = 0x0033;
    public const INVALID_CERT_TIME = 0x0034;
    public const CERT_IN_CRL = 0x0035;
    public const BAD_CRL = 0x0036;
    public const NO_VALID_CRLS = 0x0037;
    public const GET_TIME_STAMP = 0x0041;
    public const BAD_TSP_RESPONSE = 0x0042;
    public const TSP_SERVER_CERT_NOT_FOUND = 0x0043;
    public const TSP_SERVER_CERT_INVALID = 0x0044;
    public const GET_OCSP_STATUS = 0x0051;
    public const BAD_OCSP_RESPONSE = 0x0052;
    public const CERT_BAD_BY_OCSP = 0x0053;
    public const OCSP_SERVER_CERT_NOT_FOUND = 0x0054;
    public const OCSP_SERVER_CERT_INVALID = 0x0055;
    public const LDAP_ERROR = 0x0061;
}
