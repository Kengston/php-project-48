<?php

namespace Differ\FileReader;

function readFile(string $filePath): array
{
    // проверка существования файла:
    if (!file_exists($filePath)) {
        throw new \Exception("Incorrect path to file: $filePath");
    }
    $content = file_get_contents($filePath);
    $format = pathinfo($filePath, PATHINFO_EXTENSION);
    return [$content, $format];
}
