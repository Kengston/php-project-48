<?php

namespace Differ\Differ;

use function Differ\FileReader\readFile;
use function Differ\Parsers\parse;
use function Differ\DiffTreeBuilder\buildDiffTree;
use function Differ\Formatters\format;

function genDiff(string $filePath1, string $filePath2, string $formatName = 'stylish'): string
{
    [$content1, $dataType1] = readFile($filePath1);
    [$content2, $dataType2] = readFile($filePath2);
    $data1 = parse($content1, $dataType1);
    $data2 = parse($content2, $dataType2);

    $diffTree = buildDiffTree($data1, $data2);
    return format($diffTree, $formatName);
}
