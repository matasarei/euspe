<?php

namespace Matasar\Euspe\Dto;

/**
 * A private key's contents (the key file's bytes, not its path) and its password.
 * Both are kept out of var_dump() and print_r().
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

    public function __debugInfo(): array
    {
        return [
            'contents' => sprintf('*** %d bytes ***', strlen($this->contents)),
            'password' => '***',
        ];
    }
}
