<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/finance_functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_expense') {

    $category = sanitize($_POST['category'] ?? '');
    $amount = $_POST['amount'];
    $description = sanitize($_POST['description'] ?? '');
    $expense_date = sanitize($_POST['expense_date']);

    if ($category && $amount > 0 && $expense_date) {
        try {
            $query = $db->prepare("INSERT INTO expenses (category, amount, description, expense_date) VALUES (?, ?, ?, ?)");
            $query->execute([$category, $amount, $description, $expense_date]);
            go_with_message('../pages/expenses.php', 'successfully added', 'success');
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    } else {
        go_with_message('../pages/expenses.php', 'Please fill all required fields correctly', 'error');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_expense') {
    $id = $_POST['id'];
    try {
        $query = $db->prepare("DELETE FROM expenses WHERE id = ?");
        $query->execute([$id]);
        go_with_message('../pages/expenses.php', 'Expense deleted successfully', 'success');

    } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
    }
}



// Fetch Expenses Filtered Logic
$month = date('m');
$year = date('Y');

try {
    $query = $db->prepare("
        SELECT * 
        FROM expenses 
        WHERE MONTH(expense_date) = ? AND YEAR(expense_date) = ?
        ORDER BY expense_date DESC
    ");
    $query->execute([$month, $year]);
    $expenses = $query->fetchAll(PDO::FETCH_ASSOC);

    //Total Expenses
    $total_filtered = cost_money($db);


} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Expense Tracking</h1>
        <div class="user-profile">
            <span>Welcome,
                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></span>
        </div>
    </div>
    <?php display_flash_message()?>

    <div class="dashboard-row dashboard-row-spaced">

        <div class="table-container expense-form-container">
            <h2 class="section-title">Add New Expense</h2>

            <form action="expenses.php" method="POST">
                <input type="hidden" name="action" value="add_expense">

                <div class="form-group">
                    <label class="form-label" for="category">Category *</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Rent">Rent</option>
                        <option value="Electricity">Electricity</option>
                        <option value="Water">Water</option>
                        <option value="Internet">Internet</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Salaries">Salaries</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="amount">Amount *</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required
                        placeholder="0.00">
                </div>

                <div class="form-group">
                    <label class="form-label" for="expense_date">Date *</label>
                    <input type="date" class="form-control" id="expense_date" name="expense_date"
                        value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description (Optional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Additional details..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-danger">Add Expense</button>
            </form>
        </div>

        <div class="table-container dashboard-card">

            <div class="summary-box">
                <span class="summary-label">Total for Period: </span>
                <span
                    class="summary-value-danger"><?php echo show_money($total_filtered) ?></span>
            </div>

            <?php if (empty($expenses)): ?>
                <div class="empty-state">
                    <p>No expenses recorded for this period.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo date('d M Y', strtotime($expense['expense_date'])); ?></td>
                                <td><span
                                        class="badge badge-secondary"><?php echo htmlspecialchars($expense['category']); ?></span>
                                </td>
                                <td class="text-truncate-cell">
                                    <?php echo htmlspecialchars($expense['description'] ?: '-'); ?>
                                </td>
                                <td class="amount-danger">
                                    <?php echo show_money($expense['amount']); ?>
                                </td>
                                <td>
                                    <form action="expenses.php" method="POST"
                                        class="form-inline">
                                        <input type="hidden" name="action" value="delete_expense">
                                        <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                                        <button type="submit" class="btn-text-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php include '../includes/footer.php'; ?>