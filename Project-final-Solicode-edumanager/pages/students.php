<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$search = sanitize($_GET['search'] ?? '');

$params = [];
$where = "";

if ($search) {
    $where = " WHERE (full_name LIKE :search OR phone LIKE :search)";
    $params[':search'] = "%$search%";
}


try {
    $query = $db->prepare("SELECT * FROM students $where ORDER BY created_at DESC");
    $query->execute($params);
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching students: " . $e->getMessage());
}


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">
            Students
        </h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <?php display_flash_message(); ?>

    <div class="action-bar action-bar-container">
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by name or phone..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            <button type="submit" class="btn btn-primary btn-search">Search</button>
            <?php if ($search): ?>
                <a href="students.php" class="search-clear">Clear</a>
            <?php endif; ?>
        </form>
        <div class="action-buttons">
            <form action="../actions/clear_all_students.php" method="POST" class="form-no-margin">
                <input type="hidden" name="csrf_token" value="<?php echo make_token(); ?>">
                <button type="submit" class="btn btn-clear-all">Clear All Students</button>
            </form>
            <a href="add_student.php" class="btn btn-primary btn-add">+ Add Student</a>
        </div>
    </div>

    <div class="table-container">
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <p class="empty-state-text">No students found.</p>
                <p>Get started by adding a new student.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Grade</th>
                        <th>Monthly Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td><?php echo htmlspecialchars($student['grade']); ?></td>
                            <td><?php echo show_money($student['monthly_fee']); ?></td>
                            <td>
                                <?php 
                                $payment_status = get_status($student['id'], $db);
                                $status_class = 'status-'.$payment_status;
                                ?>
                                <span class="badge badge-custom <?php echo $status_class; ?>">
                                    <?php echo ucfirst(htmlspecialchars($payment_status)); ?>
                                </span>
                            </td>
                            <td>
                                <a href="student_profile.php?id=<?php echo $student['id']; ?>" class="btn-sm action-profile">Profile</a>
                                <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn-sm action-edit">Edit</a>
                                <form action="../actions/delete_student.php" method="POST" class="form-inline">
                                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
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
