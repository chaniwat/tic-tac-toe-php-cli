<?php

function waitAndReadInput(string $message): string
{
    echo $message;
    $handle = fopen("php://stdin", "r");
    return str_replace(PHP_EOL, '', fgets($handle));
}
