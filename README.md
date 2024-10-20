# euspe
![CI workflow](https://github.com/matasarei/euspe/actions/workflows/tests.yml/badge.svg)

OOP interfaces for the [EUSign PHP library](https://iit.com.ua/downloads).
The PHP extension is included in [releases](https://github.com/matasarei/euspe/releases).

## Usage
Similar to `euspe_*` functions, but with OOP interfaces.

Decrypt signed data:
```php
$crypto = new Matasar\Euspe\Crypto(); // initializes the library

try {
    // EUSignTest.php \develop()
    $result = $crypto->develop('path/to/private_key', 'password', 'encrypted_data');
    var_dump($result->signInfo->data); // decrypted data
} catch (\Matasar\Euspe\Exception\DecryptionException $e) {
    //...
}
```

Hash data for signing:
```php
$crypto = new Matasar\Euspe\Crypto();
try {
    $hash = $crypto->hash('path/to/private_key', Matasar\Euspe\Crypto::HASH_FILE);
    var_dump(base64_encode($hash));
    
    $hash = $crypto->hash('qwerty', Matasar\Euspe\Crypto::HASH_DATA); // hash string
    var_dump(base64_encode($hash));
} catch (\Matasar\Euspe\Exception\EncryptionException $e) {
    //...
}
```

Validating signature by hash:
```php
$crypto = new Matasar\Euspe\Crypto();
try {
    /** @var \Matasar\Euspe\Dto\SignInfo $info */
    $info = $crypto->verify('signature', 'hash');
} catch (\Matasar\Euspe\Exception\DecryptionException $e) {
    //...
}
```

## Tests and development
The tests don't do any real cryptography testing, as this is only a wrapper for the library.

1. Install vendors
```bash
docker run --rm -v $(pwd):/app -w /app composer:lts composer install --ignore-platform-reqs
```
> [!IMPORTANT]
> The `--ignore-platform-req=ext-eusphpe` flag is required to avoid the extension requirement.

2. Run tests
```bash
docker run --rm -v $(pwd):/app -w /app composer:lts vendor/bin/phpunit
```

## Recommendations to install the EUSign extension
1. Unpack and copy library files:
```sh
cp .../eusphpe.ini /etc/php/7.4/mods-available/eusphpe.ini
cp -R .../eusphpe_extension /usr/lib/php/eusphpe_extension
```

2. Make symlinks to the configuration file:
```sh
ln -s /etc/php/7.4/mods-available/eusphpe.ini /etc/php/7.4/fpm/conf.d/20-eusphpe.ini
ln -s /etc/php/7.4/mods-available/eusphpe.ini /etc/php/7.4/cli/conf.d/20-eusphpe.ini

```
3. Restart the FPM service
4. If you have certificates install them (by default in `/data/certificates`, see `osplm.ini`):
```sh
ls -la /data/certificates/
-rw-rw-r-- 1 root root 876543 Feb 24 2022 CACertificates.p7b
-rw-rw-r-- 1 root root  12345 Feb 24 2022 CAs.json
-rw-rw-r-- 1 root root   1234 Feb 24 2022 EU-xxxxxx.cer
-rw-rw-r-- 1 root root   1234 Feb 24 2022 EU-xxxxxx.cer
```

> [!IMPORTANT]
> You will likely need the original `osplm.ini` file and not the one which provided with the library demo.
> The `eusphpe` extension is compiled for x86_64 architecture, so it can't run on ARM natively.
 