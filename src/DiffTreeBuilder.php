<?php

namespace Differ\DiffTreeBuilder;

use function Functional\sort;

function buildDiffTree(array $data1, array $data2): array
{
    // из массивов данных создаем массивы ключей:
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $unitedKeys = array_merge($keys1, $keys2);
    $unitedUniqKeys = array_unique($unitedKeys);
    $sortedKeys = sort($unitedUniqKeys, fn ($left, $right) => $left <=> $right);

    $diffTree = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data2)) {            // ключ есть только в М1
            return ['key' => $key, 'type' => 'deleted', 'value' => $data1[$key]];
        }
        if (!array_key_exists($key, $data1)) {            // ключ есть только в М2
            return ['key' => $key, 'type' => 'added', 'value' => $data2[$key]];
        }

        if ($data1[$key] === $data2[$key]) {
            return ['key' => $key, 'type' => 'unchanged', 'value' => $data1[$key]];
        }

        if (is_array($data1[$key]) && is_array($data2[$key])) {
            return ['key' => $key, 'type' => 'nested',
                'children' => buildDiffTree($data1[$key], $data2[$key])];
        }
        return ['key' => $key, 'type' => 'changed', 'valueOld' => $data1[$key], 'valueNew' => $data2[$key]];
    }, $sortedKeys);
    return $diffTree;
}
