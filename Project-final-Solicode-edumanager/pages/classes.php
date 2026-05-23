<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';


function countStudents($db, $grade, $classroom) {
    try {
        $executeParams = [
            ':grade' => $grade,
            ':classroom' => $classroom
            ];
            
            

        $sql = "SELECT COUNT(*) FROM students WHERE grade = :grade AND classroom = :classroom";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($executeParams);
        
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
}


?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="main-content">
    <div class="top-bar">
        <h1 class="page-title">Classes</h1>
        <div class="user-profile">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <?php display_flash_message(); ?>

    <div class="action-bar">
        <div class="action-buttons">
            <a href="add_student.php" class="btn btn-primary add-btn">+ Add Student</a>
        </div>
    </div>

    <div class="accordion-container">
        
        <div class="menu-item">
            <button class="main-btn">1st Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade1&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade1', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
                <a href="class_students.php?grade=grade1&classroom=Class+B" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class B</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade1', 'Class B'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="menu-item">
            <button class="main-btn">2nd Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade2&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade2', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
                <a href="class_students.php?grade=grade2&classroom=Class+B" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class B</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade2', 'Class B'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="menu-item">
            <button class="main-btn">3rd Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade3&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade3', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="menu-item">
            <button class="main-btn">4th Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade4&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade4', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="menu-item">
            <button class="main-btn">5th Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade5&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade5', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="menu-item">
            <button class="main-btn">6th Grade <span class="arrow">▼</span></button>
            <div class="content-grid">
                <a href="class_students.php?grade=grade6&classroom=Class+A" class="class-card-link">
                    <div class="info-box">
                        <h3 class="class-title">Class A</h3>
                        <span class="student-count-badge">
                            <?php echo countStudents($db, 'grade6', 'Class A'); ?> Students
                        </span>
                    </div>
                </a>
            </div>
        </div>

    </div>
</main>

<script>
    document.querySelectorAll('.main-btn').forEach(button => {
        button.addEventListener('click', () => {
            let content = button.nextElementSibling;//the next element wich i l click in it 
            document.querySelectorAll('.content-grid').forEach(item => {
                if (item !== content) item.classList.remove('active');
            });
            content.classList.toggle('active');
        });
    });
</script>
<?php include '../includes/footer.php'; ?>