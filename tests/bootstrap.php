<?php

\error_reporting(E_ALL | E_STRICT);
\ini_set('display_errors', 1);

$vendorFilename = \dirname(__DIR__) . '/vendor/autoload.php';
if (\file_exists($vendorFilename)) {
    /* composer install */
    /** @noinspection PhpIncludeInspection */
    require $vendorFilename;
}