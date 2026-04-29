<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/core/App.php';
require_once __DIR__ . '/core/controller.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/../config/config.php';