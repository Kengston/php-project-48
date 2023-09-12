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


function run(): bool
{
    $args = \Docopt::handle(DOC, ['version' => '0.1']);

    if ($args['<firstFile>'] && $args['<secondFile>']) {
        $firstFilePath = $args['<firstFile>'];
        $secondFilePath = $args['<secondFile>'];



        if(str_contains($firstFilePath, '/') === false) {
            $firstFilePath = 'tests/fixtures/'.$firstFilePath;
        }

        if(str_contains($secondFilePath, '/') === false) {
            $secondFilePath = 'tests/fixtures/'.$secondFilePath;
        }

        $isError = false;
        if(!is_file($firstFilePath)) {
            $isError = true;
            echo "Первый файл не файл".PHP_EOL;
        }

        if(!is_file($secondFilePath)) {
            $isError = true;
            echo "Второй файл не файл".PHP_EOL;
        }

        if (!$isError) {
            print_r(findDiff($firstFilePath, $secondFilePath));
        }

        return !$isError;
    }
}



