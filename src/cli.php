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

function run()
{
    $args = \Docopt::handle(DOC, ['version' => '0.1']);

    if ($args['<firstFile>'] && $args['<secondFile>']) {
        $firstFilePath = $args['<firstFile>'];
        $secondFilePath = $args['<secondFile>'];

        print_r(findDiff($firstFilePath, $secondFilePath));
    }
}



