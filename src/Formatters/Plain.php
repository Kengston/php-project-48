<?php

namespace Differ\Formatters\Plain;

function format(array $data): string
{
    $lines = formatToPlain($data);
    return implode(PHP_EOL, $lines);
}

function formatToPlain(array $diffTree, string $path = ''): array
{
    $result = array_map(function ($node) use ($path): string {
        $property = "{$path}{$node['key']}";
        switch ($node['type']) {
            case 'deleted':
                return "Property '{$property}' was removed";

            case 'added':
                $formattedValue = toString($node['value']);
                return "Property '{$property}' was added with value: {$formattedValue}";

            case 'unchanged':
                return '';

            case 'changed':
                $formattedValueOld = toString($node['valueOld']);
                $formattedValueNew = toString($node['valueNew']);
                return "Property '{$property}' was updated. From {$formattedValueOld} to {$formattedValueNew}";

            case 'nested':
                $ancestryPath = "{$path}{$node['key']}.";
                return implode(PHP_EOL, formatToPlain($node['children'], $ancestryPath));

            default:
                throw new \Exception("Incorrect node type");
        }
    }, $diffTree);

    return array_filter($result);
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
        return '[complex value]';
    }

    if (is_numeric($value)) {
        return (string) $value; // Приводим числа к строке
    }

    return (string) $value; // Приводим другие значения к строке
}

