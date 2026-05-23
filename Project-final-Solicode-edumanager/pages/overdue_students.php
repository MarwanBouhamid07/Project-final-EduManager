<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

try {
    $query = $db->prepare("SELECT * FROM students ORDER BY created_at DESC");
    $query->execute();
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
    $overdue_students = [];
    foreach ($students as $student) {
        $status = get_status($student['id'], $db);

        if ($status == 'unpaid') {
            $overdue_students[] = $student;
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Overdue Students</h1>
        <div class="user-profile">
            <span>Welcome,
                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></span>
        </div>
    </div>

    <div class="action-bar">
        <p class="action-bar-description">Showing active students who have not made a payment for the current month.</p>
    </div>

    <div class="table-container">
        <?php if (empty($overdue_students)): ?>
            <div class="empty-state">
                <p class="empty-state-text">No overdue students found.</p>
                <p>All active students have paid for this month.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Grade</th>
                        <th>Monthly Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($overdue_students as $student): ?>
                        <tr class="rohw-debt">
                            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td><?php echo htmlspecialchars($student['grade']); ?></td>
                            <td><?php echo show_money($student['monthly_fee']);?>
                            </td>
                            <td>
                                <?php
                                $name = $student['full_name'];
                                $fee = $student['monthly_fee'];
                                $message = "السلام عليكم، نذكركم بموعد دفع الرسوم الدراسية للطالب: {$name}. المبلغ المطلوب هو: {$fee} درهم. شكراً لكم.";

                                $phone =$student['phone']; // إزالة أي رموز أو مسافات
                        
                                if (str_starts_with($phone, '0')) {
                                    $phone = '212' . substr($phone, 1);
                                }

                                $whatsapp_url = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                                ?>
                                <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank"
                                    class="btn-sm status-active btn-icon-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                                    </svg>
                                    Send Alert
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</main>

<?php include '../includes/footer.php'; ?>