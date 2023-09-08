<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function ParseData($data)
{
    $jsonData = json_decode($data, true);

    if ($jsonData !== null) {
        return $jsonData;
    }

    $yamlData = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);

    if ($yamlData !== null)
    {
        return $yamlData;
    }

    return null;
}