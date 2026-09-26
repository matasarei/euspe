<?php

namespace Matasar\Euspe\Dto;

use Matasar\Euspe\Exception\EuspeException;

/**
 * A private key's contents (the key file's bytes, not its path) and its password.
 * Both are kept out of var_dump() and print_r(), and the object cannot be serialized, so the key cannot
 * end up in a cache, a queue or a session by accident. var_export() cannot be hooked in PHP: do not export it.
 */
final class PrivateKey
{
    private string $contents;
    private string $password;

    public function __construct(string $contents, string $password)
    {
        $this->contents = $contents;
        $this->password = $password;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function __serialize(): array
    {
        throw new EuspeException('A private key cannot be serialized');
    }

    public function __unserialize(array $data): void
    {
        throw new EuspeException('A private key cannot be unserialized');
    }

    public function __debugInfo(): array
    {
        return [
            'contents' => sprintf('*** %d bytes ***', strlen($this->contents)),
            'password' => '***',
        ];
    }
}
