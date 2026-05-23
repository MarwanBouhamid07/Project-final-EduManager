<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';


$id = $_POST['id'];




try {
    $query = $db->prepare("
        SELECT p.*, s.full_name 
        FROM payments p 
        JOIN students s ON p.student_id = s.id 
        WHERE p.id = ?
    ");
    $query->execute([$id]);
    $payment = $query->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error " . $e->getMessage());
}


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Edit Payment</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="form-container">
        <?php display_flash_message(); ?>

        <form action="../actions/update_payment.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">

            <div class="form-group">
                <label class="form-label form-label-muted">Student</label>
                <div class="form-control-readonly">
                    <?php echo htmlspecialchars($payment['full_name']); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label form-label-muted">Receipt Number</label>
                <div class="form-control-readonly">
                    <?php echo htmlspecialchars($payment['receipt_number']); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="amount" class="form-label">Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01"
                    value="<?php echo $payment['amount']; ?>" required class="form-control">
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
            <div class="form-group">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" id="payment_date" name="payment_date" value="<?php echo $payment['payment_date']; ?>"
                    required class="form-control">
            </div>

            <div class="form-group">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select id="payment_method" name="payment_method" required class="form-control">
                    <option value="cash" <?php echo $payment['payment_method'] === 'cash' ? 'selected' : ''; ?>>Cash
                    </option>
                    <option value="transfer" <?php echo $payment['payment_method'] === 'transfer' ? 'selected' : ''; ?>>
                        Bank Transfer</option>
                    <option value="card" <?php echo $payment['payment_method'] === 'card' ? 'selected' : ''; ?>>Card
                    </option>
                    <option value="other" <?php echo $payment['payment_method'] === 'other' ? 'selected' : ''; ?>>Other
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary form-submit-btn">Update Payment</button>
                <a href="payments.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>