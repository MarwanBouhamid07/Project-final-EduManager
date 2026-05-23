<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $token = $_POST['csrf_token'] ?? '';
    check_token($token);

    $user_id = $_SESSION['user_id'];
    $username = sanitize($_POST['username'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($current_password)) {
        go_with_message('../pages/settings.php', 'Username and Current Password are required.', 'error');
    }

    try {
        // Fetch current user data
        $query = $db->prepare("SELECT password FROM users WHERE id = ?");
        $query->execute([$user_id]);
        $user = $query->fetch();

        if (!$user || !password_verify($current_password, $user['password'])) {
            go_with_message('../pages/settings.php', 'Invalid current password.', 'error');
        }


        // If new password is provided
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                go_with_message('../pages/settings.php', 'New passwords do not match.', 'error');
            }
            if (strlen($new_password) < 6) {
                go_with_message('../pages/settings.php', 'New password must be at least 6 characters.', 'error');
            }
        }

        $sql = "UPDATE users SET username = :username , password = :password WHERE id = :id";
    
        $query = $db->prepare($sql);
        $query->execute([':username' => $username, ':id' => $user_id , ':password' => password_hash($new_password, PASSWORD_DEFAULT)]);

        $_SESSION['username'] = $username;

        go_with_message('../pages/settings.php', 'Profile updated successfully.', 'success');

    } catch (PDOException $e) {
        go_with_message('../pages/settings.php', 'Error: ' . $e->getMessage(), 'error');
    }

}
