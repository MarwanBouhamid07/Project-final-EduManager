<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/dashboard_helpers.php';
require_once '../includes/finance_functions.php';


    $expected = expected_money($db);
    $collected = collected_money($db);
    $owed = owe_money($db);
    $cost = cost_money($db);
    $net_profit = $collected - $cost;
    
    //Monthly Revenue Chart
    $chart = chart_data($db);

    // Existing context helpers
    $recent_payments = recent_payments($db, 5);
    
    // Add data for restored charts
    $status_data = status_data($db);
    $pay_data = pay_method_data($db);


include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Financial Intelligence</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <?php display_flash_message(); ?>

    <div class="stats-grid">
        <div class="stat-card border-left-success">
            <div class="stat-title">Total Income</div>
            <div class="stat-value text-success-dark"><?php echo show_money($collected); ?></div>
            <div class="stat-change text-success">Actual receipts this month</div>
        </div>

        <div class="stat-card border-left-danger">
            <div class="stat-title">Total Expenses</div>
            <div class="stat-value text-danger-dark"><?php echo show_money($cost); ?></div>
            <div class="stat-change text-danger">Total expenses this month</div>
        </div>

        <div class="stat-card border-left-primary">
            <div class="stat-title">Net Profit</div>
            <div class="stat-value <?php echo $net_profit >= 0 ? 'text-success-dark' : 'text-danger-dark'; ?>"><?php echo show_money($net_profit); ?></div>
            <div class="stat-change <?php echo $net_profit >= 0 ? 'text-success' : 'text-danger'; ?>"> Income - Expenses</div>
        </div>

        <div class="stat-card border-left-warning">
            <div class="stat-title">Outstanding Debt</div>
            <div class="stat-value text-warning-dark"><?php echo show_money($owed); ?></div>
            <div class="stat-change text-warning">Total unpaid revenue</div>
        </div>
    </div>

    <div class="dashboard-row">
        
        <div class="table-container dashboard-card">
            <h3 class="dashboard-chart-title">Revenue Trend (Last 6 Months)</h3>
            <div class="chart-container-large">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="table-container dashboard-card">
            <h3 class="dashboard-chart-title">Payment Status (Current Month)</h3>
            <div class="chart-container-small">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>

        <div class="table-container dashboard-card">
            <h3 class="dashboard-chart-title">Revenue by Payment Method</h3>
            <div class="chart-container-small">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-row">

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Recent Collections</h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_payments as $payment): ?>
                    <tr>
                        <td class="student-name"><?php echo htmlspecialchars($payment['full_name']); ?></td>
                        <td class="payment-amount-success"><?php echo show_money($payment['amount']); ?></td>
                        <td><span class="payment-method-badge"><?php echo ucfirst($payment['payment_method']); ?></span></td>
                        <td class="payment-date"><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/dashboard_charts.js?v=<?php echo time(); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {


    // 1. Revenue Trend Chart
    try {
        const trendData = <?php echo json_encode($chart); ?>;
        window.initFinanceChart(trendData.labels, trendData.values);
    } catch (e) { console.error("Trend Chart failed:", e); }

    // 2. Student Payment Status Chart
    try {
        const statusData = <?php echo json_encode($status_data); ?>;
        window.initPaymentStatusChart(statusData.labels, statusData.values);
    } catch (e) { console.error("Status Chart failed:", e); }

    // 3. Payment Method Chart
    try {
        const methodData = <?php echo json_encode($pay_data); ?>;
        window.initPaymentMethodChart(methodData.labels, methodData.values);
    } catch (e) { console.error("Method Chart failed:", e); }
});
</script>

<?php include '../includes/footer.php'; ?>
