<?php

use Matasar\Euspe\Dto\PrivateKey;
use PHPUnit\Framework\TestCase;

class PrivateKeyTest extends TestCase
{
    public function testKeepsContentsAndPassword(): void
    {
        $key = new PrivateKey("\x00\x01key", 'secret');

        $this->assertSame("\x00\x01key", $key->getContents());
        $this->assertSame('secret', $key->getPassword());
    }

    public function testDumpsHideKeyMaterial(): void
    {
        $key = new PrivateKey('key-bytes', 'secret');

        ob_start();
        var_dump($key);
        $dump = ob_get_clean() . print_r($key, true);

        $this->assertStringNotContainsString('key-bytes', $dump);
        $this->assertStringNotContainsString('secret', $dump);
    }
}
