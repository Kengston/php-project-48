<?php

namespace Differ\Differ;

use function Funct\Collection\union;

function findDiff($pathToFile1, $pathToFile2) {
    $data1 = json_decode(file_get_contents($pathToFile1), true);
    $data2 = json_decode(file_get_contents($pathToFile2), true);

    $keys = union(array_keys($data1), array_keys($data2));

    $diff = array_map(function ($key) use ($data1, $data2) {
       if (!array_key_exists($key, $data1)) {
           return "  + {$key}: " . formatValue($data2[$key]);
       }
       if (!array_key_exists($key, $data2)) {
           return "  - {$key}: " . formatValue($data1[$key]);
       }
       if ($data1[$key] === $data2[$key]) {
           return "    {$key}: " . formatValue($data1[$key]);
       }
       return "  - {$key}: " . formatValue($data1[$key]) . PHP_EOL .
              "  + {$key}: " . formatValue($data2[$key]);
    }, $keys);

    return '{' . PHP_EOL . implode(PHP_EOL, $diff) . PHP_EOL . '}';
}

function formatValue($value) {
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return is_array($value) ? '[complex value]' : $value;
}


