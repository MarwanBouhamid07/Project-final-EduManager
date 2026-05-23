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


    $id = $_POST['id'];


    $name = sanitize($_POST['full_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $grade = sanitize($_POST['grade'] ?? '');
    $classroom = sanitize($_POST['classroom'] ?? '');
    $fee = floatval($_POST['monthly_fee'] ?? 0);

    $errors = [];

    if (empty($name)) {
        $errors[] = "Full Name is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone Number is required.";
    }
    if (empty($grade)) {
        $errors[] = "Grade is required.";
    }
    if ($fee < 0) {
        $errors[] = "Monthly Fee cannot be negative.";
    }


    if (!empty($errors)) {
        go_with_message("../pages/edit_student.php?id=$id", implode(" ", $errors), "error");
    }

    try {
        $query = $db->prepare("UPDATE students SET full_name = :full_name,classroom = :classroom, phone = :phone, email = :email, grade = :grade, monthly_fee = :monthly_fee, updated_at = NOW() WHERE id = :id");
        
        $query->execute([
            ':full_name' => $name,
            ':classroom' => $classroom,
            ':phone' => $phone,
            ':email' => $email,
            ':grade' => $grade,
            ':monthly_fee' => $fee,
            ':id' => $id
        ]);

        go_with_message("../pages/students.php", "Student updated successfully!", "success");

    } catch (PDOException $e) {
        go_with_message("../pages/edit_student.php?id=$id", "Database Error: " . $e->getMessage(), "error");
    }

} else {
    // If accessed directly without POST
    header("Location: ../pages/students.php");
    exit();
}
