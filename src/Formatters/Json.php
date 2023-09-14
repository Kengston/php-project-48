<?php

namespace Differ\Formatters\Json;

function format(array $diffTree): string
{
    return json_encode($diffTree, JSON_THROW_ON_ERROR);
}
