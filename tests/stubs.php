<?php

function euspe_init(int &$errorCode): int
{
    $errorCode = 0x0000;
    return 0;
}

function euspe_finalize(): int
{
    return 0;
}

function euspe_geterrdescr(int $iErrorCode, string &$sErrorDescription): int
{
    $sErrorDescription = $sErrorDescription . 'Dummy error description';
    return 0;
}

function euspe_setcharset(int $iCharset): int
{
    return 0;
}

function euspe_hashdata(string $data, string &$hash, int &$errorCode): int
{
    $hash = 'dummyhash';
    $errorCode = 0x0000;
    return 0;
}

function euspe_hashfile(string $filePath, string &$hash, int &$errorCode): int
{
    $hash = 'dummyhash';
    $errorCode = 0x0000;
    return 0;
}

/**
 * Verifies an internal (signature is together with the data) electronic digital signature (EDS).
 *
 * @param string $sSignData Input. Data with signature.
 * @param string $signTime Output. Signature time in the format MM.DD.YYYY HH:ii:ss.
 * @param bool $useTSP Output. Whether TSP was used.
 * @param string $issuer Output. Certificate issuer.
 * @param string $issuerCN Output. Common name of the issuer.
 * @param string $serial Output. Certificate serial number.
 * @param string $subject Output. Certificate owner.
 * @param string $subjCN Output. Common name of the owner.
 * @param string $subjOrg Output. Organization.
 * @param string $subjOrgUnit Output. Department.
 * @param string $subjTitle Output. Position.
 * @param string $subjState Output. Region.
 * @param string $subjLocality Output. City.
 * @param string $subjFullName Output. Full name.
 * @param string $subjAddress Output. Address.
 * @param string $subjPhone Output. Phone.
 * @param string $subjEMail Output. E-mail.
 * @param string $subjDNS Output. DNS.
 * @param string $subjEDRPOUCode Output. EDRPOU code.
 * @param string $subjDRFOCode Output. DRFO code.
 * @param string $signData Output. Verified data.
 * @param int $errorCode Output. Error code.
 * @return int
 */
function euspe_signverify(string $data, ?string &$signTime, bool &$useTSP, string &$issuer, string &$issuerCN, string &$serial, string &$subject, string &$subjCN, string &$subjOrg, string &$subjOrgUnit, string &$subjTitle, string &$subjState, string &$subjLocality, string &$subjFullName, string &$subjAddress, string &$subjPhone, string &$subjEMail, string &$subjDNS, string &$subjEDRPOUCode, string &$subjDRFOCode, ?string &$signData, int &$errorCode): int
{
    $signTime = 'dummysigntime';
    $useTSP = true;
    $issuer = 'dummyissuer';
    $issuerCN = 'dummyissuercn';
    $serial = 'dummyserial';
    $subject = 'dummysubject';
    $subjCN = 'dummysubjcn';
    $subjOrg = 'dummysubjorg';
    $subjOrgUnit = 'dummysubjorgunit';
    $subjTitle = 'dummysubjtitle';
    $subjState = 'dummysubjstate';
    $subjLocality = 'dummysubjlocality';
    $subjFullName = 'dummysubjfullname';
    $subjAddress = 'dummysubjaddress';
    $subjPhone = 'dummysubjphone';
    $subjEMail = 'dummysubjemail';
    $subjDNS = 'dummysubjdns';
    $subjEDRPOUCode = 'dummysubjedrpoucode';
    $subjDRFOCode = 'dummysubjdrfocode';
    $signData = 'dummysigndata';
    $errorCode = 0x0000;
    return 0;
}

function euspe_ctxcreate(?string &$context, int &$errorCode): int
{
    $context = 'dummycontext';
    $errorCode = 0x0000;
    return 0;
}

function euspe_ctxfree(string $sContext): int
{
    return 0;
}

function euspe_ctxreadprivatekeybinary(string $context, string $privateKey, string $privateKeyPassword, ?string &$privateKeyContext, int &$errorCode): int
{
    $privateKeyContext = 'dummyprivatekeycontext';
    $errorCode = 0x0000;
    return 0;
}

function euspe_ctxfreeprivatekey(string $sPrivateKeyContext): int
{
    return 0;
}

function euspe_ctxsigndata(string $sPrivateKeyContext, int $iSignAlgo, string &$sData, bool $bExternalSign, bool $bAppendCert, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxenvelopdata(string $sPrivateKeyContext, array $aRecipientCerts, int $iRecipientAppendType, bool $bSignData, bool $bAppendCert, string &$sData, string &$sEnvelopedData, int &$iErrorCode): int
{
    return 0;
}

/**
 * Decrypts the data.
 *
 * @param string $privateKeyContext Input. Pointer to the key context.
 * @param string $envData Input. Encrypted data.
 * @param string $senderCert Input. Sender's certificate.
 * @param string $data Output. Decrypted data.
 * @param string $signTime Output. Signature time in the format MM.DD.YYYY HH:ii:ss.
 * @param bool $useTSP Output. Whether TSP was used.
 * @param string $issuer Output. Certificate issuer.
 * @param string $issuerCN Output. Common name of the issuer.
 * @param string $serial Output. Certificate serial number.
 * @param string $subject Output. Certificate owner.
 * @param string $subjCN Output. Common name of the owner.
 * @param string $subjOrg Output. Organization.
 * @param string $subjOrgUnit Output. Department.
 * @param string $subjTitle Output. Position.
 * @param string $subjState Output. Region.
 * @param string $subjLocality Output. City.
 * @param string $subjFullName Output. Full name.
 * @param string $subjAddress Output. Address.
 * @param string $subjPhone Output. Phone.
 * @param string $subjEMail Output. E-mail.
 * @param string $subjDNS Output. DNS.
 * @param string $subjEDRPOUCode Output. EDRPOU code.
 * @param string $subjDRFOCode Output. DRFO code.
 * @param int $errorCode Output. Error code.
 * @return int
 */
function euspe_ctxdevelopdata(string $privateKeyContext, string $envData, string $envSenderCert = null, ?string &$data, ?string &$signTime, bool &$useTSP, string &$issuer, string &$issuerCN, string &$serial, string &$subject, string &$subjCN, string &$subjOrg, string &$subjOrgUnit, string &$subjTitle, string &$subjState, string &$subjLocality, string &$subjFullName, string &$subjAddress, string &$subjPhone, string &$subjEMail, string &$subjDNS, string &$subjEDRPOUCode, string &$subjDRFOCode, int &$errorCode): int
{
    $data = 'dummydata';
    $signTime = 'dummysigntime';
    $useTSP = true;
    $issuer = 'dummyissuer';
    $issuerCN = 'dummyissuercn';
    $serial = 'dummyserial';
    $subject = 'dummysubject';
    $subjCN = 'dummysubjcn';
    $subjOrg = 'dummysubjorg';
    $subjOrgUnit = 'dummysubjorgunit';
    $subjTitle = 'dummysubjtitle';
    $subjState = 'dummysubjstate';
    $subjLocality = 'dummysubjlocality';
    $subjFullName = 'dummysubjfullname';
    $subjAddress = 'dummysubjaddress';
    $subjPhone = 'dummysubjphone';
    $subjEMail = 'dummysubjemail';
    $subjDNS = 'dummysubjdns';
    $subjEDRPOUCode = 'dummysubjedrpoucode';
    $subjDRFOCode = 'dummysubjdrfocode';
    $errorCode = 0x0000;
    return 0;
}

function euspe_generateprngsequence(int $iLength, string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_getfilestoresettings(string $sPath, bool $bCheckCRLs, bool $bAutoRefresh, bool $bOwnCRLsOnly, bool $bFullAndDeltaCRLs, bool $bAutoDownloadCRLs, bool $bSaveLoadedCerts, int $iExpireTime, int &$iErrorCode): int
{
    return 0;
}

function euspe_setruntimeparameter(string $sParameterName, $vParameterValue, int &$iErrorCode): int
{
    return 0;
}

function euspe_setocspresponseexpiretime(int $iExpireTime, int &$iErrorCode): int
{
    return 0;
}

function euspe_parsecert(string &$sCertificate, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_getcrinfo(string &$sCR, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_savecertificate(string &$sCertificate, int &$iErrorCode): int
{
    return 0;
}

function euspe_getcertificatesbykeyinfo(string &$sKeyInfo, array $aCMPServers, array $aCMPServersPorts, string &$sCertificates, int &$iErrorCode): int
{
    return 0;
}

function euspe_enumkeymediatypes(int $iTypeIndex, string &$sTypeName, int &$iErrorCode): int
{
    return 0;
}

function euspe_enumkeymediadevices(int $iTypeIndex, int $iDeviceIndex, string &$sDeviceName, int &$iErrorCode): int
{
    return 0;
}

function euspe_isprivatekeyreaded(bool &$isReaded, int &$iErrorCode): int
{
    return 0;
}

function euspe_readprivatekey(int &$iErrorCode): int
{
    return 0;
}

function euspe_readprivatekeysilently(int $iTypeIndex, int $iDeviceIndex, string $sPassword, int &$iErrorCode): int
{
    return 0;
}

function euspe_readprivatekeybinary(string &$sPrivateKey, string $password, int &$iErrorCode): int
{
    return 0;
}

function euspe_readprivatekeyfile(string $sPrivateKeyFile, string $sPassword, int &$iErrorCode): int
{
    return 0;
}

function euspe_resetprivatekey(): int
{
    return 0;
}

function euspe_enumjksprivatekeys(string &$sContainer, int $iIndex, string &$keyAlias, int &$iErrorCode): int
{
    return 0;
}

function euspe_getjksprivatekey(string &$sContainer, string $keyAlias, string &$sPrivateKey, array &$aCertificates, int &$iErrorCode): int
{
    return 0;
}

function euspe_getowncertificate(int $iCertKeyType, int $iKeyUsage, &$info, string &$sCertificate, int &$iErrorCode): int
{
    return 0;
}

function euspe_getkeyinfosilently(int $iTypeIndex, int $iDeviceIndex, string $sPassword, string &$sKeyInfo, int &$iErrorCode): int
{
    return 0;
}

function euspe_getkeyinfobinary(string &$sPrivateKey, string $sPassword, string &$sKeyInfo, int &$iErrorCode): int
{
    return 0;
}

function euspe_generateprivatekey2(int $iTypeIndex, int $iDeviceIndex, string $sPassword, bool $setKeyMediaPassword, int $UAKeysType, int $UADSKeysSpec, int $UAKEPKeysSpec, string $UAParamsPath, int $internationalKeysType, int $RSAKeysSpec, string $RSAParamsPath, int $ECDSAKeysSpec, string $ECDSAParamsPath, &$info, string $extKeyUsages, string &$sPrivateKey, string &$sPrivateKeyInfo, string &$UARequest, string $UAReqFileName, string &$UAKEPRequest, string $UAKEPReqFileName, string &$RSARequest, string $RSAReqFileName, string &$ECDSARequest, string $ECDSAReqFileName, int &$iErrorCode): int
{
    return 0;
}

function euspe_hashdatacontinue(string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_hashdataend(string &$sHash, int &$iErrorCode): int
{
    return 0;
}

function euspe_hashdata_withparams(string &$sCert, string &$sData, string &$sHash, int &$iErrorCode): int
{
    return 0;
}

function euspe_hashdatabegin_withparams(string &$sCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_hashfile_withparams(string $sCert, string $sFileName, string &$sHash, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsignscount(string &$sSign, int &$iCount, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsigntype(int $iSignIndex, string &$sSign, int &$iSignType, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsignerinfo(int $iSignIndex, string &$sSign, string &$sIssuer, string &$sIssuerCN, string &$sSerial, string &$sSubject, string &$sSubjCN, string &$sSubjOrg, string &$sSubjOrgUnit, string &$sSubjTitle, string &$sSubjState, string &$sSubjLocality, string &$sSubjFullName, string &$sSubjAddress, string &$sSubjPhone, string &$sSubjEMail, string &$sSubjDNS, string &$sSubjEDRPOUCode, string &$sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsignerinfoex(int $iSignIndex, string &$sSign, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsigntimeinfo(int $iSignIndex, string &$sSign, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_signhash(string &$sHash, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_verifyhashsign(string &$sHash, string &$sSign, string $sSignTime, bool $bUseTSP, string &$sIssuer, string &$sIssuerCN, string &$sSerial, string &$sSubject, string &$sSubjCN, string &$sSubjOrg, string &$sSubjOrgUnit, string &$sSubjTitle, string &$sSubjState, string &$sSubjLocality, string &$sSubjFullName, string &$sSubjAddress, string &$sSubjPhone, string &$sSubjEMail, string &$sSubjDNS, string &$sSubjEDRPOUCode, string &$sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_verifyhashsignontime(string &$sHash, int $iSignIndex, string &$sSign, string $sOnTime, bool $bOffline, bool $bNoCRL, string &$sSignTime, bool $bUseTSP, string &$sIssuer, string &$sIssuerCN, string &$sSerial, string &$sSubject, string &$sSubjCN, string &$sSubjOrg, string &$sSubjOrgUnit, string &$sSubjTitle, string &$sSubjState, string &$sSubjLocality, string &$sSubjFullName, string &$sSubjAddress, string &$sSubjPhone, string &$sSubjEMail, string &$sSubjDNS, string &$sSubjEDRPOUCode, string &$sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_signcreate(string &$sData, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_signverifyontime(int $iSignIndex, string &$sData, string $sOnTime, bool $bOffline, bool $bNoCRL, string &$sSignTime, bool $bUseTSP, string &$sIssuer, string &$sIssuerCN, string &$sSerial, string &$sSubject, string &$sSubjCN, string &$sSubjOrg, string &$sSubjOrgUnit, string &$sSubjTitle, string &$sSubjState, string &$sSubjLocality, string &$sSubjFullName, string &$sSubjAddress, string &$sSubjPhone, string &$sSubjEMail, string &$sSubjDNS, string &$sSubjEDRPOUCode, string &$sSubjDRFOCode, string &$sResultData, int &$iErrorCode): int
{
    return 0;
}

function euspe_signcreateext(string &$sData, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_signverifyext(string &$sData, string &$sSign, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_signverifyextontime(string &$sData, int $iSignIndex, string &$sSign, string $sOnTime, bool $bOffline, bool $bNoCRL, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_createemptysign(string &$sData, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_createsigner(string &$sHash, string &$sSigner, int &$iErrorCode): int
{
    return 0;
}

function euspe_appendsigner(string &$sSigner, string &$sSignerCert, string &$sPreviousSign, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_signfile(string $sFileName, string $sFileNameWithSign, bool $bExternal, int &$iErrorCode): int
{
    return 0;
}

function euspe_verifyfile(string $sFileNameWithSign, string $sFileName, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawsignhash(string &$sHash, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawverifyhashsign(string &$sHash, string &$sSign, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawsign(string &$sData, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawverifysign(string &$sData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawsignfile(string $sFileName, string $sSignFileName, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawverifyfilesign(string $sSignFileName, string $sFileName, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxrawsignhash(string $sPrivateKeyContext, string &$sHash, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxrawsign(string $sPrivateKeyContext, string &$sData, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_getsigner(int $iSignIndex, string &$sSign, string &$sSigner, int &$iErrorCode): int
{
    return 0;
}

function euspe_getfilesigner(int $iSignIndex, string $sFileNameWithSign, string &$sSigner, int &$iErrorCode): int
{
    return 0;
}

function euspe_appendvalidationdatatosigner(string &$sPreviousSigner, string &$sSignerCert, string &$sSigner, int &$iErrorCode): int
{
    return 0;
}

function euspe_getdatafromsigneddata(string &$sSign, string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_getdatafromsignedfile(string $sFileNameWithSign, string $sFileName, int &$iErrorCode): int
{
    return 0;
}

function euspe_getcertificatefromsigneddata(int $iIndex, string $sSignedData, &$info, string &$sCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawenvelop(string &$sRecipientCert, string &$sData, string &$sEnvelopedData, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawdevelop(string &$sEnvData, string &$sData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_envelop_to_recipients(array $aRecipientCerts, bool $bSignData, string &$sData, string &$sEnvelopedData, int &$iErrorCode): int
{
    return 0;
}

function euspe_develop(string &$sEnvData, string &$sData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_envelopfile_to_recipients(array $aRecipientCerts, bool $bSignData, string $sFileName, string $sEnvelopedFileName, int &$iErrorCode): int
{
    return 0;
}

function euspe_developfile(string $sEnvelopedFileName, string $sFileName, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessiondestroy(string $sSession, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsessioncreate_step1(int $iExpireTime, string &$sSession, string &$sClientData, int &$iErrorCode): int
{
    return 0;
}

function euspe_serversessioncreate_step1(int $iExpireTime, string &$sClientData, string &$sSession, string &$sServerData, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsessioncreate_step2(string $sSession, string &$sServerData, string &$sClientData, int &$iErrorCode): int
{
    return 0;
}

function euspe_serversessioncreate_step2(string $sSession, string &$sClientData, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessionisinitialized(string $sSession, bool &$isInitialized, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessiongetpeercertinfo(string $sSession, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessionsave(string $sSession, string &$sSessionData, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessionload(string &$sSessionData, string &$sSession, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessioncheckcertificates(string $sSession, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessionencrypt(string $sSession, string &$sData, string &$sEncryptedData, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessiondecrypt(string $sSession, string &$sEncryptedData, string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessionencryptcontinue(string $sSession, string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_sessiondecryptcontinue(string $sSession, string &$sEncryptedData, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientdynamickeysessioncreate_cert(int $iExpireTime, string &$sServerCert, string &$sSession, string &$sClientData, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientdynamickeysessioncreate(int $iExpireTime, string $sServerCertIssuer, string $sServerCertSerial, string &$sSession, string &$sClientData, int &$iErrorCode): int
{
    return 0;
}

function euspe_serverdynamickeysessioncreate(int $iExpireTime, string &$sClientData, string &$sSession, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientrawmultisessioncreate(int $iExpireTime, string &$sServerData, string &$sSession, int &$iErrorCode): int
{
    return 0;
}

function euspe_serverrawmultisessioncreate(int $iExpireTime, array $aClientsCerts, string &$sSession, string &$sServerData, int &$iErrorCode): int
{
    return 0;
}

function euspe_rawmultisessionaddclients(string $sSession, array $aClientsCerts, string &$sServerData, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxsetparameter(string $sContext, string $sParameterName, $vParameterValue, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxreadprivatekey(string $sContext, string &$sPrivateKeyContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxreadprivatekeysilently(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string &$sPrivateKeyContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxreadnamedprivatekey(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string $sNamedPrivateKeyLabel, string $sNamedPrivateKeyPassword, string &$sPrivateKeyContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxisnamedprivatekeyexists(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string $sNamedPrivateKeyLabel, string $sNamedPrivateKeyPassword, bool &$bIsExists, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxdestroynamedprivatekey(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string $sNamedPrivateKeyLabel, string $sNamedPrivateKeyPassword, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxchangenamedprivatekeypassword(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string $sNamedPrivateKeyLabel, string $sNamedPrivateKeyPassword, string $sNamedPrivateKeyNewPassword, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxgeneratenamedprivatekey2ex(string $sContext, int $iTypeIndex, int $iDeviceIndex, string $sPassword, string $sNamedPrivateKeyLabel, string $sNamedPrivateKeyPassword, bool $setKeyMediaPassword, int $UAKeysType, int $UADSKeysSpec, int $UAKEPKeysSpec, string $UAParamsPath, int $internationalKeysType, int $RSAKeysSpec, string $RSAParamsPath, int $ECDSAKeysSpec, string $ECDSAParamsPath, $info, string $extKeyUsages, string &$sPrivateKeyContext, string &$UARequest, string $UAReqFileName, string &$UAKEPRequest, string $UAKEPReqFileName, string &$RSARequest, string $RSAReqFileName, string &$ECDSARequest, string $ECDSAReqFileName, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxreadprivatekeyfile(string $sContext, string $sPrivateKeyFile, string $sPassword, string &$sPrivateKeyContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxgetowncertificate(string $sPrivateKeyContext, int $iCertKeyType, int $iKeyUsage, &$info, string &$sCertificate, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxhashdata(string $sContext, int $iHashAlgo, string &$sCertificate, string &$sData, string &$sHash, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxhashdatabegin(string $sContext, int $iHashAlgo, string &$sCertificate, string &$sHashContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxhashdatacontinue(string $sHashContext, string &$sData, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxhashdataend(string $sHashContext, string &$sHash, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxfreehash(string $sHashContext): int
{
    return 0;
}

function euspe_ctxsignhashvalue(string $sPrivateKeyContext, int $iSignAlgo, string &$sHash, bool $bAppendCert, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxisalreadysigned(string $sPrivateKeyContext, int $iSignAlgo, string &$sSign, bool &$bIsAlreadySigned, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxappendsignhashvalue(string $sPrivateKeyContext, int $iSignAlgo, string &$sHash, string &$sPreviousSign, bool $bAppendCert, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxappendsign(string $sPrivateKeyContext, int $iSignAlgo, string &$sData, string &$sPreviousSign, bool $bAppendCert, string &$sSign, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxenvelopdatawithsettings(string $sPrivateKeyContext, array $aRecipientCerts, int $iRecipientAppendType, bool $bCheckRecipientCertsOffline, bool $bCheckRecipientCertsNoCRL, bool $bNoRecipientCertsCertCheck, bool $bSign, bool $bNoTSP, bool $bAppendCert, string &$sData, string &$sEnvelopedData, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetasictype(string &$sASiCData, int &$iASiCType, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsigntype(string &$sASiCData, int &$iSignType, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsignscount(string &$sASiCData, int &$iSignsCount, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsignlevel(int $iSignIndex, string &$sASiCData, int &$iSignLevel, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicisallcontentcovered(int $iSignIndex, string &$sASiCData, bool &$bIsCovered, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsignerinfo(int $iSignIndex, string &$sASiCData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxasicgetsignerinfo(string $sContext, int $iSignIndex, string &$sASiCData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsigntimeinfo(int $iSignIndex, string &$sASiCData, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetsignreferences(int $iSignIndex, string &$sASiCData, array &$aReferences, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicgetreference(string &$sASiCData, string $sReference, string &$sReferenceData, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicsigndata(int $iASiCType, int $iSignType, int $iSignLevel, array $aReferences, array $aReferencesData, string &$sASiCData, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicverifydata(int $iSignIndex, string &$sASiCData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxasicsigndata(string $sPrivateKeyContext, int $iSignAlgo, int $iASiCType, int $iSignType, int $iSignLevel, array $aReferences, array $aReferencesData, string &$sASiCData, int &$iErrorCode): int
{
    return 0;
}

function euspe_asicappendsign(int $iSignLevel, array $aReferences, string &$sPreviousASiCData, string &$sASiCData, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxasicappendsign(string $sPrivateKeyContext, int $iSignAlgo, int $iSignLevel, array $aReferences, string &$sPreviousASiCData, string &$sASiCData, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfgetsignscount(string &$sSignedPDFData, int &$iSignsCount, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfgetsigntype(int $iSignIndex, string &$sSignedPDFData, int &$iSignLevel, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfgetsignerinfo(int $iSignIndex, string &$sSignedPDFData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxpdfgetsignerinfo(string $sContext, int $iSignIndex, string &$sSignedPDFData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfgetsigntimeinfo(int $iSignIndex, string &$sSignedPDFData, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfsigndata(string &$sPDFData, int $iSignType, string &$sSignedPDFData, int &$iErrorCode): int
{
    return 0;
}

function euspe_pdfverifydata(int $iSignIndex, string &$sSignedPDFData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxpdfsigndata(string $sPrivateKeyContext, int $iSignAlgo, string &$sPDFData, int $iSignType, string &$sSignedPDFData, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgettype(string &$sXAdESData, int &$iSignType, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetsignscount(string &$sXAdESData, int &$iSignsCount, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetsignlevel(int $iSignIndex, string &$sXAdESData, int &$iSignLevel, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetsignerinfo(int $iSignIndex, string &$sXAdESData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxxadesgetsignerinfo(string $sContext, int $iSignIndex, string &$sXAdESData, &$info, string &$sSignerCert, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetsigntimeinfo(int $iSignIndex, string &$sXAdESData, &$info, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetsignreferences(int $iSignIndex, string &$sXAdESData, array &$aReferences, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesgetreference(string &$sXAdESData, string $sReference, string &$sReferenceData, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadessigndata(int $iXAdESType, int $iSignLevel, array $aReferences, array $aReferencesData, string &$sXAdESData, int &$iErrorCode): int
{
    return 0;
}

function euspe_xadesverifydata(array $aReferences, array $aReferencesData, int $iSignIndex, string &$sXAdESData, string $sSignTime, bool $bUseTSP, string $sIssuer, string $sIssuerCN, string $sSerial, string $sSubject, string $sSubjCN, string $sSubjOrg, string $sSubjOrgUnit, string $sSubjTitle, string $sSubjState, string $sSubjLocality, string $sSubjFullName, string $sSubjAddress, string $sSubjPhone, string $sSubjEMail, string $sSubjDNS, string $sSubjEDRPOUCode, string $sSubjDRFOCode, int &$iErrorCode): int
{
    return 0;
}

function euspe_ctxxadessigndata(string $sPrivateKeyContext, int $iSignAlgo, int $iXAdESType, int $iSignLevel, array $aReferences, array $aReferencesData, string &$sASiCData, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsctxopen(string $sTypeName, string $sDeviceName, string $sPassword, string &$sClientsContext, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsctxclose(string $sClientsContext): int
{
    return 0;
}

function euspe_clientsctxenumclients(string $sClientsContext, int $iIndex, string &$sClientName, string &$sClientLogin, int &$iClientType, string &$sClientPassword, string &$sClientAddress, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsctxaddclient(string $sClientsContext, string $sClientName, string $sClientLogin, int $iClientType, string $sClientPassword, string $sClientAddress, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsctxchangeclient(string $sClientsContext, string $sClientName, string $sNewClientName, string $sNewClientLogin, int $iNewClientType, string $sNewClientPassword, string $sNewClientAddress, int &$iErrorCode): int
{
    return 0;
}

function euspe_clientsctxremoveclient(string $sClientsContext, string $sClientName, int &$iErrorCode): int
{
    return 0;
}
