<?php

namespace Differ\Cli;

require_once __DIR__ . '/../vendor/autoload.php';

use function Differ\Differ\findDiff;

const DOC = <<<DOC
gendiff -h

Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOC;

function resolvePath($path)
{
    if (file_exists($path)) {
        return $path;
    } elseif (file_exists(__DIR__ . '/tests/fixtures/' . $path)) {
        return __DIR__ . '/tests/fixtures/' . $path;
    } else {
        return null;
    }
}

function run()
{
    $args = \Docopt::handle(DOC, ['version' => '0.1']);

    if ($args['<firstFile>'] && $args['<secondFile>']) {
        $firstFilePath = $args['<firstFile>'];
        $secondFilePath = $args['<secondFile>'];



        $firstFilePathResolved = resolvePath($firstFilePath);
        $secondFilePathResolved = resolvePath($secondFilePath);

        if ($firstFilePathResolved === null || $secondFilePathResolved === null) {
            echo "Error: One or both files not found.\n";
            return;
        }
        print_r(findDiff($firstFilePathResolved, $secondFilePathResolved));
    }
}



