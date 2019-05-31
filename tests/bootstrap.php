<?php

$autoloadFile = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoloadFile)) {
    throw new \RuntimeException('Did not find vendor/autoload.php. Did you run "composer install --dev"?');
}

require_once $autoloadFile;
