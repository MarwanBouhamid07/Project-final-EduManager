
<?php

require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';





?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Add Student</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="form-container">
        
        <?php display_flash_message(); ?>

        <form action="../actions/add_student.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">

            <div class="form-group form-group-spaced">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="full_name" name="full_name" required class="form-control">
            </div>

            <div class="form-group form-group-spaced">
                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" id="phone" name="phone" required class="form-control">
            </div>

            <div class="form-group form-group-spaced">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>

            <div class="form-group form-group-spaced">
                <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                <!-- <input type="text" id="grade" name="grade" required class="form-control"> -->
                 <select name="grade" id="grade" class="form-control">
                    <option value="grade1">1st Grade</option>
                    <option value="grade2">2nd Grade</option>
                    <option value="grade3">3rd Grade</option>
                    <option value="grade4">4th Grade</option>
                    <option value="grade5">5th Grade</option>
                    <option value="grade6">6th Grade</option>
                 </select>
            </div>

            <div class="form-group form-group-spaced">
                <label for="classroom" class="form-label">Classroom <span class="text-danger">*</span></label>
                 <select name="classroom" id="classroom" required class="form-control">
                    <option value="Class A">Class A</option>
                    <option value="Class B">Class B</option>
                 </select>
            </div>
           

            <div class="form-group form-group-spaced">
                <label for="monthly_fee" class="form-label">Monthly Fee <span class="text-danger">*</span></label>
                <input type="number" id="monthly_fee" name="monthly_fee" step="0.01" min="0" required class="form-control">
            </div>


            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-no-margin">Save Student</button>
                <a href="students.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>

</main>

<?php include '../includes/footer.php'; ?>
