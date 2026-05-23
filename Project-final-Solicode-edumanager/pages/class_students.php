<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$search = sanitize($_GET['search'] ?? '');
$filter_grade = sanitize($_GET['grade'] ?? '');
$filter_class = sanitize($_GET['classroom'] ?? '');


if (empty($filter_grade) || empty($filter_class)) {
    header("Location: classes.php");
    exit;
}

$params = [
    ':grade' => $filter_grade,
    ':class' => $filter_class
];
$where = "";

if ($search) {
    $where = " AND (full_name LIKE :search OR phone LIKE :search)";
    $params[':search'] = "%$search%";
}

try {
    $sql = "SELECT * FROM students WHERE grade = :grade AND classroom = :class $where ORDER BY created_at DESC";
    $query = $db->prepare($sql);
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
        <div class="top-bar-left">
            <a href="classes.php" class="btn btn-secondary btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            
        </div>
        
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>
    
    <?php display_flash_message(); ?>

    <div class="action-bar">
        <form method="GET" class="search-form">
            <input type="hidden" name="grade" value="<?php echo htmlspecialchars($filter_grade); ?>">
            <input type="hidden" name="classroom" value="<?php echo htmlspecialchars($filter_class); ?>">

            <input type="text" name="search" placeholder="Search in this class..."
                value="<?php echo htmlspecialchars($search); ?>" class="search-input">
            <button type="submit" class="btn btn-primary btn-search">Search</button>
            <?php if ($search): ?>
                <a href="class_students.php?grade=<?php echo urlencode($filter_grade); ?>&classroom=<?php echo urlencode($filter_class); ?>"
                class="search-clear">Clear</a>
                <?php endif; ?>
        </form>
        <a href="add_student.php" class="btn btn-primary btn-add">+ Add Student</a>
    </div>
    
    <div class="table-container">
        <?php if (empty($students)): ?>
            <div class="empty-state">
                <i class="fas fa-folder-open empty-state-icon"></i>
                <p class="empty-state-text">No students found in this class.</p>
                <a href="add_student.php" class="btn btn-primary">+ Add New Student</a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
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
                            <td><?php echo show_money($student['monthly_fee']); ?></td>
                            <td>
                                <?php $payment_status = get_status($student['id'], $db);?>
                                <span class="badge badge-custom status-<?php echo $payment_status; ?>">
                                    <?php echo htmlspecialchars($payment_status); ?>
                                </span>
                            </td>
                            <td>
                                <a href="student_profile.php?id=<?php echo $student['id']; ?>"
                                    class="btn-sm action-profile">Profile</a>
                                <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn-sm action-edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>