<aside class="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-header">
            <div class="logo-container">
                <i class="fas fa-graduation-cap"></i>
                <span>EduManager</span>
            </div>
            <button type="button" id="sidebarToggle" class="sidebar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="students.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </a>
            <a href="classes.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Classes</span>
            </a>
            <a href="payments.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : ''; ?>">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
            <a href="expenses.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'expenses.php' ? 'active' : ''; ?>">
                <i class="fas fa-wallet"></i>
                <span>Expenses</span>
            </a>
            <a href="overdue_students.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'overdue_students.php' ? 'active' : ''; ?>">
                <i class="fas fa-exclamation-circle"></i>
                <span>Overdue Students</span>
            </a>
            <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <a href="../actions/logout.php" class="nav-link logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>