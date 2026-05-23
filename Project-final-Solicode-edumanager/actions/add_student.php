<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

check_login();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['csrf_token'] ?? '';
    if (!check_token($token)) {
        go_with_message("../pages/add_student.php", "error in securty.", "error");
    }

    $name = sanitize($_POST['full_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $grade = sanitize($_POST['grade'] ?? '');
    $class = sanitize($_POST['classroom'] ?? '');
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
    if (empty($class)) {
        $errors[] = "Classroom is required.";
    }
    if ($fee < 0) {
        $errors[] = "Monthly Fee cannot be negative.";
    }

    // Check for validation errors
    if (!empty($errors)) {
        go_with_message("../pages/add_student.php", implode(" ", $errors), "error");
    }

    try {
        $query = $db->prepare("INSERT INTO students (full_name, phone, email, grade, classroom, monthly_fee, registration_date) VALUES (:full_name, :phone, :email, :grade, :classroom, :monthly_fee, NOW())");
        
        $query->execute([
            ':full_name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':grade' => $grade,
            ':classroom' => $class,
            ':monthly_fee' => $fee,
        ]);


        go_with_message("../pages/students.php", "Student added successfully.", "success");

    } catch (PDOException $e) {

        go_with_message("../pages/add_student.php", "Error: " . $e->getMessage(), "error");
    }

}

