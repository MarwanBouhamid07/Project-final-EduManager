<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';



include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Settings / Profile</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="form-container settings-container">
        <?php display_flash_message(); ?>

        <form action="../actions/update_profile.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">
            
            <div class="form-group">
                <label for="username" class="settings-label">New Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required class="settings-input">
                <small class="form-text-muted">Leave as is to keep your current username.</small>
            </div>

            <hr class="settings-divider">
            <h3 class="settings-subtitle">Change Password</h3>

            <div class="form-group">
                <label for="current_password" class="settings-label">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="settings-input">
                <small class="form-text-muted">Required to verify your identity.</small>
            </div>

            <div class="form-group">
                <label for="new_password" class="settings-label">New Password</label>
                <input type="password" id="new_password" name="new_password" class="settings-input">
                <small class="form-text-muted">Leave blank if you don't want to change your password.</small>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="settings-label">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="settings-input">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-block settings-btn">Update Profile</button>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
