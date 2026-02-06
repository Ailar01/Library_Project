<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class BasicTest extends TestCase
{
    public function testProjectHasComposerAutoload(): void
    {
        $this->assertFileExists(__DIR__ . '/../vendor/autoload.php');
    }

    public function testBooksFolderExists(): void
    {
        $this->assertDirectoryExists(__DIR__ . '/../books');
    }
}
