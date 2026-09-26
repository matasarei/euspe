<?php

use Matasar\Euspe\Dto\PrivateKey;
use Matasar\Euspe\Exception\EuspeException;
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

    public function testCannotBeSerialized(): void
    {
        $this->expectException(EuspeException::class);

        serialize(new PrivateKey('key-bytes', 'secret'));
    }

    public function testCannotBeUnserialized(): void
    {
        $this->expectException(EuspeException::class);

        unserialize('O:28:"Matasar\\Euspe\\Dto\\PrivateKey":2:{s:8:"contents";s:3:"key";s:8:"password";s:2:"pw";}');
    }
}
