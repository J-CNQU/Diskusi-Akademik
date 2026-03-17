<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'core/App.php';
require_once 'core/controller.php';
require_once 'core/database.php';
require_once '../config/config.php';