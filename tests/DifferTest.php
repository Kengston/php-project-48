<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\findDiff;

class DifferTest extends TestCase
{
    public function testDiffJsonFlat()
    {
        $diff = findDiff(__DIR__ . '/files/file1.json', __DIR__ . '/files/file2.json');

        $this->assertStringEqualsFile(__DIR__ . '/files/diff.txt', $diff);
    }
}
