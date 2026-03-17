<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;

if (file_exists($file) && is_file($file)) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
        header("Content-Type: text/css");
    }
    return false;
}

require_once __DIR__ . '/index.php';