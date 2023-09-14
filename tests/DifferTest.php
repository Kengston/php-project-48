<?php

namespace GenDiff\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider addDataProvider
     */
    public function testDiffer($filepathExpectedDiff, $filepath1, $filepath2, $format)
    {
        $this->assertStringEqualsFile($filepathExpectedDiff, genDiff($filepath1, $filepath2, $format));
    }

    public function addDataProvider()
    {
        $filePathJson1 =  $this->getFixtureFilepath('file1.json');
        $filePathJson2 = $this->getFixtureFilepath('file2.json');
        $filePathYml1 = $this->getFixtureFilepath('file1.yml');
        $filePathYml2 = $this->getFixtureFilepath('file2.yml');
        $filePathExpectedDiffStylish = $this->getFixtureFilepath('correctDiffStylish');
        $filePathExpectedDiffPlain = $this->getFixtureFilepath('correctDiffPlain');
        $filePathExpectedDiffJson = $this->getFixtureFilepath('correctDiffJson');

        return [
            [$filePathExpectedDiffStylish, $filePathJson1, $filePathJson2, 'stylish'],
            [$filePathExpectedDiffStylish, $filePathYml1, $filePathYml2, 'stylish'],
            [$filePathExpectedDiffPlain, $filePathJson1, $filePathJson2, 'plain'],
            [$filePathExpectedDiffPlain, $filePathYml1, $filePathYml2, 'plain'],
            [$filePathExpectedDiffJson, $filePathJson1, $filePathJson2, 'json'],
            [$filePathExpectedDiffJson, $filePathYml1, $filePathYml2, 'json']
        ];
    }

    public function testDifferDefaultJson()
    {
        $filepathExpectedDiff = $this->getFixtureFilepath('correctDiffStylish');
        $resultDiff = genDiff($this->getFixtureFilepath('file1.json'), $this->getFixtureFilepath('file2.json'));
        $this-> assertStringEqualsFile($filepathExpectedDiff, $resultDiff);
    }

    public function testDifferDefaultYaml()
    {
        $filepathExpectedDiff = $this->getFixtureFilepath('correctDiffStylish');
        $resultDiff = genDiff($this->getFixtureFilepath('file1.yml'), $this->getFixtureFilepath('file2.yml'));
        $this-> assertStringEqualsFile($filepathExpectedDiff, $resultDiff);
    }

    public function getFixtureFilepath(string $filename): string
    {
        return __DIR__ . "/fixtures/{$filename}";
    }
}
