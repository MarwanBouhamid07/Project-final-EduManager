<?php
// Start Session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/functions.php';
require_once '../includes/flash_messages.php';


check_login();
?>
