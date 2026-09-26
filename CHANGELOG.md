# Changelog

## 2.0.0 (unreleased)

### Breaking
- `Crypto` is removed. The library is split into a process-wide `EusignSession` and three services
  that take it: `Hasher`, `SignatureVerifier` and `EnvelopeDeveloper`.
- PHP `~7.4.0 || ^8.1`. PHP 8.0 is excluded because IIT ships no `eusphpe` build for it.
- `develop()` takes a `Dto\PrivateKey` (key file **contents** and password) instead of two strings.
- DTOs are built by the library through constructors. `DevelopResult` no longer exposes `context`
  and `privateKeyContext`, which pointed to contexts already freed.
- Every exception extends `Exception\EuspeException`, a `RuntimeException`. `EncryptionException`
  and `DecryptionException` used to extend `LogicException`, so `catch (\LogicException)` no longer
  catches them.
- `verifyHash()` throws `Exception\VerificationException`. It extends `DecryptionException`, so
  existing `catch (DecryptionException)` blocks still work.
- Error messages show the code in hex, `ERR 0x0033: …`, and `getCode()` returns the real code (51).

### Fixed
- The library was initialised on every `new Crypto()` and finalised on every destruct, so two
  overlapping instances tore it down under each other. It is now initialised once per process
  (once per request under PHP-FPM) and finalised only by an explicit `EusignSession::close()`.
- Error codes went through `dechex()` and back: `0x33` was reported as 33, and codes with a hex
  letter such as `0xFF` raised a `TypeError` instead of the library's error.
- `develop()` frees its contexts in `finally`. A failure while freeing no longer replaces the
  original exception, and a context that was never created is no longer freed.
- No implicitly nullable parameters (deprecated in PHP 8.4).

Hash and signature output is byte-identical to 1.x.

### Migrating from `new Crypto()`
Build the session once, for example in your container, and inject it:

```php
$session = new EusignSession();
$hasher = new Hasher($session);
$verifier = new SignatureVerifier($session);
$developer = new EnvelopeDeveloper($session);
```

| 1.x | 2.0 |
|---|---|
| `$crypto->hash($data, Crypto::HASH_DATA)` | `$hasher->hashData($data)` |
| `$crypto->hash($path, Crypto::HASH_FILE)` | `$hasher->hashFile($path)` |
| `$crypto->verify($signature, $hash)` | `$verifier->verifyHash($signature, $hash)` |
| `$crypto->develop($key, $password, $data, $cert)` | `$developer->develop(new PrivateKey($key, $password), $data, $cert)` |
| `catch (DecryptionException)` around `verify()` | unchanged, or `catch (VerificationException)` |

Arguments and results are raw binary, as before. `develop()` always took the key's contents, not a
path, although the 1.x README said otherwise.

## 1.1.0 (2024-10-20)
- Signature verification by hash: `Crypto::verify()`.
- Error messages are no longer translated.

## 1.0.1 (2024-07-14)
- Initial version.
