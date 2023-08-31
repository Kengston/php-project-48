<?php

namespace Differ;

use Funct\Collection as F;

function genDiff($pathToFile1, $pathToFile2) {
    $data1 = json_decode(file_get_contents($pathToFile1), true);
    $data2 = json_decode(file_get_contents($pathToFile2), true);

    $keys = F\union(array_keys($data1), array_keys($data2));

    return $keys;
}

print_r(genDiff("file1.json", "file2.json"));
