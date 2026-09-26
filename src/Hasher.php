<?php

namespace Matasar\Euspe;

use Matasar\Euspe\Enum\Error;
use Matasar\Euspe\Exception\EncryptionException;
use Matasar\Euspe\Handler\ErrorHandler;

class Hasher
{
    private EusignSession $session;

    public function __construct(EusignSession $session)
    {
        $this->session = $session;
    }

    /**
     * @return string raw binary hash
     *
     * @throws EncryptionException
     */
    public function hashData(string $data): string
    {
        $this->session->open();

        $e = new ErrorHandler(EncryptionException::class, Error::UNKNOWN);
        $hash = '';
        $e->assert(euspe_hashdata($data, $hash, $e->errorCode), 'Failed to hash data');

        return $this->nonEmpty($hash, $e);
    }

    /**
     * @return string raw binary hash
     *
     * @throws EncryptionException
     */
    public function hashFile(string $path): string
    {
        $this->session->open();

        $e = new ErrorHandler(EncryptionException::class, Error::UNKNOWN);
        $hash = '';
        $e->assert(euspe_hashfile($path, $hash, $e->errorCode), 'Failed to hash file');

        return $this->nonEmpty($hash, $e);
    }

    private function nonEmpty(string $hash, ErrorHandler $e): string
    {
        if (empty($hash)) {
            throw new EncryptionException('Hash generation failed', $e->errorCode);
        }

        return $hash;
    }
}
