<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): array
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);

        case 'yml':
        case 'yaml':
            return Yaml::parse($data);

        default:
            throw new \Exception("Incorrect file format: $format");
    }
}
