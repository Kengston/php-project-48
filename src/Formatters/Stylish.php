<?php

namespace Differ\Formatters\Stylish;

const BASE_INDENT = '    ';

function format(array $data): string
{
    $lines = formatToStylish($data);
    return '{' . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . '}';
}

function formatToStylish(array $diffTree, int $depth = 0): array
{
    $indent = getIndent($depth);
    $nextDepth = $depth + 1;
    $result = array_map(function ($node) use ($indent, $nextDepth): string {
        switch ($node['type']) {
            case 'deleted':
                $value = $node['value'];
                $formattedValue = toString($value, $nextDepth);
                return "{$indent}  - {$node['key']}: {$formattedValue}";

            case 'added':
                $value = $node['value'];
                $formattedValue = toString($value, $nextDepth);
                return "{$indent}  + {$node['key']}: {$formattedValue}";

            case 'unchanged':
                $value = $node['value'];
                $formattedValue = toString($value, $nextDepth);
                return "{$indent}    {$node['key']}: {$formattedValue}";

            case 'changed':
                $valueOld = $node['valueOld'];
                $formattedValueOld = toString($valueOld, $nextDepth);
                $valueNew = $node['valueNew'];
                $formattedValueNew = toString($valueNew, $nextDepth);
                return "{$indent}  - {$node['key']}: {$formattedValueOld}" . PHP_EOL .
                       "{$indent}  + {$node['key']}: {$formattedValueNew}";

            case 'nested':
                $stringNested = implode(PHP_EOL, formatToStylish($node['children'], $nextDepth));
                return "{$indent}    {$node['key']}: {" . PHP_EOL .
                     "{$stringNested}" . PHP_EOL . "{$indent}    }";

            default:
                throw new \Exception("Incorrect node type: {$node['type']}");
        }
    }, $diffTree);
    return $result;
}

function toString($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_array($value)) {
        $result = arrayToString($value, $depth);
        $indent = getIndent($depth);
        $bracketsResult = "{{$result}" . PHP_EOL . "{$indent}}";
        return $bracketsResult;
    }

    return "{$value}";
}

function arrayToString(array $arrayValue, int $depth): string
{
    $keys = array_keys($arrayValue);
    $inDepth = $depth + 1;
    $result = array_map(function ($key) use ($arrayValue, $inDepth): string {
        $val = toString($arrayValue[$key], $inDepth);
        $indent = getIndent($inDepth);
        $result = PHP_EOL . "{$indent}{$key}: {$val}";
        return $result;
    }, $keys);
    return implode('', $result);
}

function getIndent(int $multiplier): string
{
    return str_repeat(BASE_INDENT, $multiplier);
}
