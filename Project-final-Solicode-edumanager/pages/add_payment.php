<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

try {
    $query = $db->prepare("SELECT id, full_name, monthly_fee FROM students ORDER BY full_name ASC");
    $query->execute();
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error : " . $e->getMessage());
}


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Add Payment</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="form-container">
        <?php display_flash_message(); ?>

        <form action="../actions/add_payment.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">

            <div class="form-group form-group-spaced">
                <label for="student_id" class="form-label">Student</label>
                <select id="student_id" name="student_id" required class="form-control">
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>">
                            <?php echo htmlspecialchars($student['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group form-group-spaced">
                <label for="amount" class="form-label">Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" required class="form-control">
            </div>
            <div class="form-group">
                <label>Start Paying From Month</label>
                <input type="month" name="start_month" value="<?php echo date('Y-m'); ?>" required class="form-control">
            </div>


            <div class="form-group">
                <label>How Many Months?</label>
                <select name="months_count" class="form-control" required>
                    <option value="1">1 Month</option>
                    <option value="2">2 Months</option>
                    <option value="3">3 Months</option>
                    <option value="4">4 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">1 Year (12 Months)</option>
                </select>
            </div>


            <div class="form-group form-group-spaced">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" id="payment_date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" required
                    class="form-control">
            </div>

            <div class="form-group form-group-spaced">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select id="payment_method" name="payment_method" required class="form-control">
                    <option value="cash">Cash</option>
                    <option value="transfer">Bank Transfer</option>
                    <option value="card">Card</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-no-margin">Record Payment</button>
                <a href="payments.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<script></script>

<?php include '../includes/footer.php'; ?>