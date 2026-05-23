<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';


check_login();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['csrf_token'] ?? '';
    if (!check_token($token)) {
        go_with_message("../pages/students.php", "error in security.", "error");
    }

    $id = $_POST['id'];


    try {
        $query = $db->prepare("DELETE FROM students WHERE id = :id");
        $query->execute([':id' => $id]);

        go_with_message("../pages/students.php", "Student deleted successfully", "success");

    } catch (PDOException $e) {
        go_with_message("../pages/students.php", "Error: " . $e->getMessage(), "error");
    }

}
