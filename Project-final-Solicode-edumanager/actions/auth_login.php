<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!check_token($token)) {
        go_with_message('../pages/login', 'error in securty', 'error');
    }


    // Get and sanitize inputs
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        go_with_message('../pages/login', 'All fields are required.', 'error');
    }

    try {
        $query = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $query->execute([':username' => $username]);
        $user = $query->fetch();

        // 3. Verify password
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent Session Fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];


            header("Location: ../pages/dashboard.php");
            exit();
        } else {

            go_with_message('../pages/login', 'Invalid username or password.', 'error');
        }

    } catch (PDOException $e) {
        go_with_message('../pages/login', 'error.', 'error');
    }

}
?>