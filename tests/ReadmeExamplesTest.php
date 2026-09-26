<?php

use Matasar\Euspe\EusignSession;
use PHPUnit\Framework\TestCase;

/**
 * Runs every ```php block of README.md, in order and in one scope, against the stubs,
 * so the examples cannot drift from the API.
 */
class ReadmeExamplesTest extends TestCase
{
    private const README = __DIR__ . '/../README.md';

    protected function setUp(): void
    {
        require_once 'stubs.php';

        EuspeStub::reset();
    }

    protected function tearDown(): void
    {
        (new EusignSession())->close();
    }

    public function testEveryPhpExampleRuns(): void
    {
        $blocks = $this->phpBlocks();
        $this->assertGreaterThanOrEqual(4, count($blocks));

        // Inputs the examples take from the reader's own application
        $documentPath = __FILE__;
        $signatureBase64 = base64_encode('signature');
        $hashBase64 = base64_encode('hash');
        $keyPath = __FILE__;
        $keyPassword = 'password';
        $envelope = 'envelope';

        ob_start();

        try {
            foreach ($blocks as $block) {
                eval($block);
            }
        } finally {
            $output = ob_get_clean();
        }

        $this->assertStringContainsString(base64_encode('dummyhash'), $output);
        $this->assertStringContainsString('John Doe, signed ', $output);
        $this->assertStringContainsString('dummydata', $output);
        $this->assertSame(1, array_count_values(EuspeStub::calledFunctions())['euspe_init']);
    }

    public function testErrorExampleShowsTheCurrentMessageFormat(): void
    {
        EuspeStub::failOn('euspe_verifyhashsign', 0x0023);
        $blocks = $this->phpBlocks();
        $verifyBlock = array_values(array_filter($blocks, fn (string $block) => strpos($block, 'verifyHash') !== false))[0];

        $signatureBase64 = base64_encode('signature');
        $hashBase64 = base64_encode('hash');

        ob_start();

        try {
            eval($blocks[0]);
            eval($verifyBlock);
        } finally {
            $output = ob_get_clean();
        }

        $this->assertStringStartsWith('Failed to verify sign; ERR 0x0023: ', $output);
    }

    public function testReadmeHasNoOutdatedClaims(): void
    {
        $readme = file_get_contents(self::README);
        $lines = explode("\n", $readme);

        $this->assertSame('# euspe: OOP wrapper for the IIT EUSign PHP extension', $lines[0]);
        $this->assertStringNotContainsString('included in', $readme);
        $this->assertStringNotContainsString('new Crypto()', preg_replace('/^## Upgrading from 1\.x.*?(?=^## )/ms', '', $readme));
        $this->assertStringNotContainsString('x86_64 architecture', $readme);
        $this->assertStringNotContainsString('/etc/php/7.4', $readme);
        $this->assertStringNotContainsString('--ignore-platform-reqs', $readme);
        $this->assertStringNotContainsString('downloads', implode("\n", array_slice($lines, 1, 3)));
    }

    /**
     * @return string[]
     */
    private function phpBlocks(): array
    {
        preg_match_all('/^```php\n(.*?)^```/ms', file_get_contents(self::README), $matches);

        return $matches[1];
    }
}
