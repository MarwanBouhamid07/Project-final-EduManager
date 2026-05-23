<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';



$student_id = $_GET['id'];

try {
    $query = $db->prepare("SELECT * FROM students WHERE id = ?");
    $query->execute([$student_id]);
    $student = $query->fetch(PDO::FETCH_ASSOC);



    $query = $db->prepare("SELECT * FROM payments WHERE student_id = ? ORDER BY payment_date DESC");
    $query->execute([$student_id]);
    $payments = $query->fetchAll(PDO::FETCH_ASSOC);

    $total_paid = 0;
    foreach ($payments as $p) {
        $total_paid += $p['amount'];
    }

    $late_months = late_months($student_id, $db);
    $current_status = get_status($student_id, $db);

} catch (PDOException $e) {
    die("error: " . $e->getMessage());
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Student Profile</h1>
        <div class="action-buttons">
            <a href="edit_student.php?id=<?php echo $student_id; ?>" class="btn btn-secondary btn-edit-profile">Edit Profile</a>
        </div>
    </div>

    <div class="dashboard-row dashboard-row-spaced">
        
        <!-- Student Info Card -->
        <div class="profile-sidebar">
            <div class="table-container dashboard-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($student['full_name'], 0, 1)); ?>
                    </div>
                    <h2 class="profile-name"><?php echo htmlspecialchars($student['full_name']); ?></h2>
                    <span class="status-badge <?php echo 'status-' . strtolower($current_status); ?>">
                        Current: <?php echo ucfirst($current_status); ?>
                    </span>
                </div>

                <div class="profile-details">
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Phone:</span>
                        <span class="profile-detail-value"><?php echo htmlspecialchars($student['phone']); ?></span>
                    </div>
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Email:</span>
                        <span class="profile-detail-value small"><?php echo htmlspecialchars($student['email']); ?></span>
                    </div>
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Grade:</span>
                        <span class="profile-detail-value"><?php echo htmlspecialchars($student['grade']); ?></span>
                    </div>
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Monthly Fee:</span>
                        <span class="profile-detail-value bold"><?php echo show_money($student['monthly_fee']); ?></span>
                    </div>
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Billing Day:</span>
                        <span class="profile-detail-value">Day <?php echo $student['billing_day']; ?></span>
                    </div>
                    <div class="profile-detail-row">
                        <span class="profile-detail-label">Joined:</span>
                        <span class="profile-detail-value"><?php echo date('M d, Y', strtotime($student['registration_date'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Analytics Summary -->
            <div class="stats-grid profile-stats">
                <div class="stat-card stat-card-sm">
                    <div class="stat-title">Total Payments</div>
                    <div class="stat-value stat-value-success"><?php echo show_money($total_paid); ?></div>
                    <div class="stat-change text-muted-alt">All time</div>
                </div>
                <div class="stat-card stat-card-sm">
                    <div class="stat-title">Late Months</div>
                    <div class="stat-value <?php echo $late_months > 0 ? 'stat-value-danger' : 'stat-value-success'; ?>"><?php echo $late_months; ?></div>
                    <div class="stat-change text-muted-alt">Overdue months since registration</div>
                </div>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="table-container">
            <div class="action-bar table-header-padded">
                <h2 class="table-title-bold">Payment History</h2>
                <a href="add_payment.php?student_id=<?php echo $student_id; ?>" class="btn btn-primary btn-sm-add">Add New Payment</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Receipt #</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="4" class="empty-state-cell">No payments recorded yet.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td class="font-weight-medium"><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                            <td class="font-weight-semibold"><?php echo show_money($payment['amount']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($payment['payment_method'])); ?></td>
                            <td class="text-muted-alt small"><?php echo htmlspecialchars($payment['receipt_number']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
