<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['csrf_token'] ?? '';
    if (!check_token($token)) {
        go_with_message("../pages/payments.php", "error in securty", "error");
    }
    $id = $_POST['id'];


    try {
        $query = $db->prepare("DELETE FROM payments WHERE id = :id");
        $query->execute([':id' => $id]);

        go_with_message("../pages/payments.php", "Payment record deleted successfully.", "success");

    } catch (PDOException $e) {
        go_with_message("../pages/payments.php", " Error: " . $e->getMessage(), "error");
    }

}