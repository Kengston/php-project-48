<?php

namespace Differ;

use function Funct\Collection\union;

function findDiff($pathToFile1, $pathToFile2) {
    $data1 = json_decode(file_get_contents($pathToFile1), true);
    $data2 = json_decode(file_get_contents($pathToFile2), true);

    return union(array_keys($data1), array_keys($data2));
}


