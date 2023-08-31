<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Differ\findDiff;

$diff = findDiff("file1.json", "file2.json");
print_r($diff);