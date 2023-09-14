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

function formatValue($value, $depth)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_array($value)) {
        $formattedValue = formatArray($value, $depth);
        return "{" . PHP_EOL . $formattedValue . PHP_EOL . str_repeat('    ', $depth - 1) . "}";
    }

    return $value;
}

function formatArray($array, $depth)
{
    $indentation = str_repeat('    ', $depth);
    $lines = [];

    foreach ($array as $key => $value) {
        $formattedValue = is_array($value) ? formatValue($value, $depth + 1) : $value;
        $lines[] = "{$indentation}{$key}: {$formattedValue}";
    }

    return implode(PHP_EOL, $lines);
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

function formatDiffStylish($diff)
{
    $result = formatDiff($diff);
    return "{\n" . implode("\n", $result) . "\n}";
}

function formatDiff($diff, $depth = 1)
{
    $indentation = str_repeat('    ', $depth);
    $lines = [];

    foreach ($diff as $item) {
        $key = $item['key'];
        $type = $item['type'];

        switch ($type) {
            case 'added':
                $formattedValue = formatValue($item['value'], $depth + 1);
                $lines[] = "{$indentation}+ {$key}: {$formattedValue}";
                break;
            case 'removed':
                $formattedValue = formatValue($item['value'], $depth + 1);
                $lines[] = "{$indentation}- {$key}: {$formattedValue}";
                break;
            case 'unchanged':
                $formattedValue = formatValue($item['value'], $depth + 1);
                $lines[] = "{$indentation}  {$key}: {$formattedValue}";
                break;
            case 'changed':
                $formattedOldValue = formatValue($item['oldValue'], $depth + 1);
                $formattedNewValue = formatValue($item['newValue'], $depth + 1);
                $lines[] = "{$indentation}- {$key}: {$formattedOldValue}";
                $lines[] = "{$indentation}+ {$key}: {$formattedNewValue}";
                break;
            case 'nested':
                $lines[] = "{$indentation}  {$key}: {";
                $nestedLines = formatDiff($item['children'], $depth + 1);
                $lines[] = implode("\n", $nestedLines);
                $lines[] = "{$indentation}  }";
                break;
        }
    }

    return $lines;
}





