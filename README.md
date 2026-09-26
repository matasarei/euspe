# euspe: OOP wrapper for the IIT EUSign PHP extension
[![CI](https://github.com/matasarei/euspe/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/matasarei/euspe/actions/workflows/tests.yml?query=branch%3Amain)
[![PHP](https://img.shields.io/packagist/dependency-v/matasarei/euspe/php)](https://packagist.org/packages/matasarei/euspe)
[![Packagist](https://img.shields.io/packagist/v/matasarei/euspe)](https://packagist.org/packages/matasarei/euspe)

A typed, object-oriented layer over the `euspe_*` functions of IIT's EUSign PHP extension (`eusphpe`).

> [!NOTE]
> The `eusphpe` extension is IIT's proprietary software and is **not distributed here**. Get it from
> IIT: the latest `EUSPHPE-<date>.zip` archive is listed in
> [IDInfoProcessingD.pdf](https://id.gov.ua/downloads/IDInfoProcessingD.pdf) and on
> [iit.com.ua/downloads](https://iit.com.ua/downloads). This library is MIT-licensed; the extension is not.

```bash
composer require matasarei/euspe
```

## Usage
Build one `EusignSession` and inject it into the services you need. The library is initialised once
per process (once per request under PHP-FPM), the first time a service uses it, however many
sessions and services you build.

```php
use Matasar\Euspe\EnvelopeDeveloper;
use Matasar\Euspe\EusignSession;
use Matasar\Euspe\Hasher;
use Matasar\Euspe\SignatureVerifier;

$session = new EusignSession(); // UTF-8; pass Encoding::CP1251 for Windows-1251

$hasher = new Hasher($session);
$verifier = new SignatureVerifier($session);
$developer = new EnvelopeDeveloper($session);
```

Every value going in and out is **raw binary** unless it is text (names, times, decrypted data).
Encode with `base64_encode()` yourself when you store or send it.

Hash data or a file for signing:
```php
$hash = $hasher->hashData('qwerty');          // raw binary
$fileHash = $hasher->hashFile($documentPath); // raw binary

echo base64_encode($fileHash);
```

Verify a signature by hash (both arguments raw binary):
```php
use Matasar\Euspe\Exception\VerificationException;

try {
    $info = $verifier->verifyHash(base64_decode($signatureBase64), base64_decode($hashBase64));

    echo $info->signerInfo->subjFullName, ', signed ', $info->signTime;
} catch (VerificationException $e) {
    echo $e->getMessage(); // e.g. "Failed to verify sign; ERR 0x0023: …"
}
```

Decrypt an envelope and verify the signature inside it. `PrivateKey` takes the key file's
**contents**, not its path, and keeps both the key and the password out of `var_dump()`:
```php
use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\Exception\DecryptionException;

$key = new PrivateKey(file_get_contents($keyPath), $keyPassword);

try {
    $result = $developer->develop($key, $envelope); // add the sender's certificate if the envelope lacks it

    echo $result->envelopInfo->data;                       // decrypted data
    echo $result->signInfo->signerInfo->subjFullName;      // who signed it
} catch (DecryptionException $e) {
    echo $e->getMessage();
}
```

Every exception extends `Matasar\Euspe\Exception\EuspeException`; `getCode()` is IIT's error code
(see `Matasar\Euspe\Enum\Error`), shown in the message as `ERR 0x%04X`.

The library stays initialised until the process ends. A long-running worker that needs to release
it can call `$session->close()`; the next service call opens it again.

## Upgrading from 1.x
`new Crypto()` is gone: build one `EusignSession` and inject it into `Hasher`, `SignatureVerifier` and
`EnvelopeDeveloper`. The mapping, and every other breaking change, is in [CHANGELOG.md](CHANGELOG.md).

## Installing the extension
### Supported builds
From IIT's archive `EUSPHPE-20260825.zip`; check the latest archive for changes.

| Arch | PHP builds |
|---|---|
| Linux x86-64 | 7.2, 7.4, 8.1, 8.2, 8.3, 8.4, 8.5 |
| Linux ARM64 | 8.1, 8.3, 8.4, 8.5 |

Each build comes in an NTS variant and a thread-safe `.ts` variant. There is **no PHP 8.0 build**,
so this library requires `~7.4.0 || ^8.1`.

### Steps
1. Download the latest `EUSPHPE-<date>.zip` from IIT (see the note at the top).
2. Pick the build that matches your server: `Modules/Linux/64/` for x86-64 or `Modules/Linux/ARM64/`
   for ARM64, then the `eusphpei.<arch>.<php>.tar` for your PHP version. Check `php -v`: it says
   `NTS` or `ZTS`; take the `.ts` variant for ZTS.
3. Unpack it into a directory of its own, for example `/usr/lib/php/eusphpe_extension/`. It holds
   `eusphpe.so`, the IIT libraries it loads, and `osplm.ini`.
4. Enable the extension with its absolute path. No `LD_LIBRARY_PATH` is needed: `eusphpe.so` finds
   its libraries in its own directory.
   ```sh
   # Debian/Ubuntu packages (replace <version>, e.g. 8.4)
   echo 'extension=/usr/lib/php/eusphpe_extension/eusphpe.so' > /etc/php/<version>/mods-available/eusphpe.ini
   phpenmod -v <version> eusphpe

   # official php Docker images
   echo 'extension=/usr/lib/php/eusphpe_extension/eusphpe.so' > "$PHP_INI_DIR/conf.d/20-eusphpe.ini"
   ```
5. Restart PHP-FPM and check: `php -r 'var_dump(extension_loaded("eusphpe"));'`

In a multi-arch Docker build, keep the unpacked builds side by side and let `TARGETARCH` pick one:
```dockerfile
FROM php:8.4-fpm
ARG TARGETARCH
# eusphpe/amd64/ and eusphpe/arm64/ hold the unpacked IIT builds for this PHP version
COPY eusphpe/${TARGETARCH}/ /usr/lib/php/eusphpe_extension/
RUN echo 'extension=/usr/lib/php/eusphpe_extension/eusphpe.so' > "$PHP_INI_DIR/conf.d/20-eusphpe.ini"
COPY certificates/ /data/certificates/
```

### Certificates and `osplm.ini`
The library checks signatures against the CA certificates in its file store. Download IIT's current
[CACertificates.p7b](https://iit.com.ua/download/productfiles/CACertificates.p7b) and
[CAs.json](https://iit.com.ua/download/productfiles/CAs.json) into that directory, and refresh them
when IIT publishes new ones.

`osplm.ini`, next to `eusphpe.so`, is the library's configuration. Review it rather than keeping
the demo values:

| Section (under `…\End User`) | Keys | What to set |
|---|---|---|
| `FileStore` | `Path` | The certificate directory, `/data/certificates` by default. The same path appears under `Key Medias\File System\Folders`. |
| `FileStore` | `CheckCRLs`, `AutoDownloadCRLs` | Whether revocation lists are checked and downloaded. |
| `Mode` | `Offline` | `1` makes no network calls; set `0` to use OCSP, TSP or CRL downloads. |
| `Proxy` | `Use`, `Address`, `Port`, `User`, `Password` | Only if outbound traffic goes through a proxy. |
| `OCSP` | `Use`, `Address`, `Port` | Online certificate status; the CA's addresses are in `CAs.json`. |
| `TSP` | `GetStamps`, `Address`, `Port` | Time-stamping for signatures you create. |

IIT's own documentation for the extension, `EUSignPHPDescription.doc` and `EUSignPHPAppendixZ.doc`,
is on [iit.com.ua/downloads](https://iit.com.ua/downloads).

## Tests and development
The tests run against stubs of the `euspe_*` functions (`tests/stubs.php`), so they need neither the
extension nor real keys. They check how the library calls the extension, not the cryptography.

```bash
docker run --rm -v "$PWD":/app -w /app composer:lts composer install --ignore-platform-req=ext-eusphpe
docker run --rm -v "$PWD":/app -w /app composer:lts vendor/bin/phpunit
```

`--ignore-platform-req=ext-eusphpe` skips only the extension check; every other platform
requirement still applies. Every PHP example in this README is run by `tests/ReadmeExamplesTest.php`.
