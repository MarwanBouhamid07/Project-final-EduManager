<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';


check_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['csrf_token'] ?? '';
    if (!check_token($token)) {
        go_with_message("../pages/students.php", "error in securty.", "error");
    }

    try {
        $query = $db->prepare("DELETE FROM students");
        $query->execute();

        go_with_message("../pages/students.php", "All students have been cleared.", "success");

    } catch (PDOException $e) {
        go_with_message("../pages/students.php", "Error: " . $e->getMessage(), "error");
    }

}

