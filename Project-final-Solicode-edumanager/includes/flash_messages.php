<?php
// Display Flash Message

function display_flash_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';

        echo "
        <div class='alert alert-{$type} alert-spaced'>
            " . htmlspecialchars($message) . "
        </div>
        ";

        unset($_SESSION['message'], $_SESSION['message_type']);
    }
}
?>
