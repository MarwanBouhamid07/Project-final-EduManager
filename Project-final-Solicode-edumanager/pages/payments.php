<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';


$btn_month = $_GET['month'] ?? '';
$btn_method = $_GET['method'] ?? '';
$params = [];
$where = [];

if ($btn_month) {
    $where[] = "p.payment_date LIKE :month";
    $params[':month'] = "$btn_month%";
}

if ($btn_method) {
    $where[] = "p.payment_method = :method";
    $params[':method'] = $btn_method;
}

$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";



try {
    $query = $db->prepare("
        SELECT p.*, s.full_name 
        FROM payments p 
        JOIN students s ON p.student_id = s.id 
        $where_sql
        ORDER BY p.payment_date DESC, p.created_at DESC
    ");
    $query->execute($params);
    $payments = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error  " . $e->getMessage());
}



$current_date = date('Y-m-d');
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Payments</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <?php display_flash_message(); ?>

    <div class="action-bar">
        <form method="GET" class="filter-form-payments">
            <div class="filter-group">
                <label class="filter-label">Month</label>
                <input type="month" name="month" value="<?php echo htmlspecialchars($btn_month); ?>"
                    class="filter-input">
            </div>
            <div class="filter-group">
                <label class="filter-label">Method</label>
                <select name="method" class="filter-select">
                    <option value="">All Methods</option>
                    <option value="cash" <?php echo $btn_method === 'cash' ? 'selected' : ''; ?>>Cash</option>
                    <option value="transfer" <?php echo $btn_method === 'transfer' ? 'selected' : ''; ?>>Transfer</option>
                    <option value="card" <?php echo $btn_method === 'card' ? 'selected' : ''; ?>>Card</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-filter-payments">Filter</button>
                <?php if ($btn_month || $btn_method): ?>
                    <a href="payments.php" class="filter-clear">Clear</a>
                <?php endif; ?>
            </div>
        </form>
        <a href="add_payment.php" class="btn btn-primary btn-add-payment">+ Add Payment</a>
    </div>

    <div class="table-container">
        <?php if (empty($payments)): ?>
            <div class="empty-state">
                <p class="empty-state-text">No payments found.</p>
                <p>Record your first payment to get started.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Student Name</th>
                        <th>Amount</th>
                        <th>Payment Month</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['receipt_number']); ?></td>
                            <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                            <td><?php echo show_money($payment['amount']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_month']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($payment['payment_method'])); ?></td>
                            <td>
                                <span class="badge badge-paid">Paid</span>
                            </td>
                            <td>
                                <a href="edit_payment.php?id=<?php echo $payment['id']; ?>" class="btn-sm action-edit">Edit</a>
                                <a href="../actions/print_receipt.php?id=<?php echo $payment['id']; ?>"
                                    class="btn-sm action-print" target="_blank">Print PDF</a>
                                <form action="../actions/delete_payment.php" method="POST" class="form-inline">
                                    <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">
                                    <button type="submit" class="btn-sm action-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>