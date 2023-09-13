<?php

namespace Differ\Differ;

use function Funct\Collection\union;
use function Differ\Parsers\ParseData;

function findDiff($pathToFile1, $pathToFile2)
{
    $data1 = parseData(file_get_contents($pathToFile1));
    $data2 = parseData(file_get_contents($pathToFile2));

    $diff = generateDiff($data1, $data2);

    return formatDiffStylish($diff);
}

function formatValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return is_array($value) ? '[complex value]' : $value;
}

function generateDiff($data1, $data2)
{
    $keys = union(array_keys($data1), array_keys($data2));
    sort($keys);

    $diff = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $data2[$key],
            ];
        }
        if (!array_key_exists($key, $data2)) {
            return [
                'key' => $key,
                'type' => 'removed',
                'value' => $data1[$key],
            ];
        }
        if (is_array($data1[$key]) && is_array($data2[$key])) {
            // Recursively generate nested differences
            $children = generateDiff($data1[$key], $data2[$key]);
            return [
                'key' => $key,
                'type' => 'nested',
                'children' => $children,
            ];
        }
        if ($data1[$key] === $data2[$key]) {
            return [
                'key' => $key,
                'type' => 'unchanged',
                'value' => $data1[$key],
            ];
        }
        return [
            'key' => $key,
            'type' => 'changed',
            'oldValue' => $data1[$key],
            'newValue' => $data2[$key],
        ];
    }, $keys);

    return $diff;
}

function formatDiffStylish($diff, $depth = 1)
{
    $indentation = str_repeat('    ', $depth - 1);
    $lines = [];

    foreach ($diff as $item) {
        $key = $item['key'];
        $type = $item['type'];

        switch ($type) {
            case 'added':
                $formattedValue = formatValue($item['value'], $depth);
                $lines[] = "{$indentation}  + {$key}: {$formattedValue}";
                break;
            case 'removed':
                $formattedValue = formatValue($item['value'], $depth);
                $lines[] = "{$indentation}  - {$key}: {$formattedValue}";
                break;
            case 'unchanged':
                $formattedValue = formatValue($item['value'], $depth);
                $lines[] = "{$indentation}    {$key}: {$formattedValue}";
                break;
            case 'changed':
                $formattedOldValue = formatValue($item['oldValue'], $depth);
                $formattedNewValue = formatValue($item['newValue'], $depth);
                $lines[] = "{$indentation}  - {$key}: {$formattedOldValue}";
                $lines[] = "{$indentation}  + {$key}: {$formattedNewValue}";
                break;
            case 'nested':
                $nestedLines = formatDiffStylish($item['children'], $depth + 1);
                $lines[] = "{$indentation}    {$key}: {" . PHP_EOL . $nestedLines . PHP_EOL . "{$indentation}    }";
                break;
        }
    }

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "{$indentation}}";
}



