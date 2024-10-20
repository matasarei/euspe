<?php

use Matasar\Euspe\Crypto;
use Matasar\Euspe\Dto\DevelopResult;
use Matasar\Euspe\Dto\SignInfo;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    private Crypto $crypto;

    protected function setUp(): void
    {
        require_once 'stubs.php';

        $this->crypto = new Crypto();
    }

    public function testDevelop()
    {
        $privateKey = 'your_private_key';
        $privateKeyPassword = 'your_private_key_password';
        $protectedData = 'your_protected_data';

        $result = $this->crypto->develop($privateKey, $privateKeyPassword, $protectedData);

        $this->assertInstanceOf(DevelopResult::class, $result);
    }

    public function testHash()
    {
        $hash = $this->crypto->hash('test_data', false);
        $this->assertNotEmpty($hash);

        $hash = $this->crypto->hash(__FILE__, true);
        $this->assertNotEmpty($hash);
    }

    public function testVerify()
    {
        $result = $this->crypto->verify('signature', 'hash');

        $this->assertInstanceOf(SignInfo::class, $result);
    }
}
