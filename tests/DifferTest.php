<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\findDiff;

class DifferTest extends TestCase
{
    public function testDiffJsonFlat()
    {
        $diff = findDiff(__DIR__ . '/fixtures/file1.json', __DIR__ . '/fixtures/file2.json');

        $this->assertStringEqualsFile(__DIR__ . '/fixtures/diff.txt', $diff);
    }

    public function testDiffYamFlat()
    {
        $diff = findDiff(__DIR__ . '/fixtures/filepath1.yml', __DIR__ . '/fixtures/filepath2.yml');

        $this->assertStringEqualsFile(__DIR__ . '/fixtures/diff.txt', $diff);
    }
}
