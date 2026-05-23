<?php

require_once  '../includes/functions.php';



function expected_money($db) {
    $query = $db->query("SELECT SUM(monthly_fee) FROM students");
    return (float)($query->fetchColumn());
}

//Sum of payments received in the current month/year

function collected_money($db) {
    $month = date('m');
    $year = date('Y');
    $query = $db->prepare("
        SELECT SUM(amount) 
        FROM payments 
        WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?
    ");
    $query->execute([$month, $year]);
    return (float)($query->fetchColumn() ?: 0);
}

//Total Expenses

function cost_money($db) {
    $month = date('m');
    $year = date('Y');
    $query = $db->prepare("
        SELECT SUM(amount) 
        FROM expenses 
        WHERE MONTH(expense_date) = ? AND YEAR(expense_date) = ?
    ");
    $query->execute([$month, $year]);
    return (float)($query->fetchColumn() ?: 0);
}

//Logic: (months_enrolled) - (months_paid)

function owe_money($db) {
    $query = $db->query("SELECT id, monthly_fee, registration_date FROM students");
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $debt = 0;
    foreach ($students as $student) {
        $late_months = late_months_calc($student, $db);
        $debt += ($late_months * $student['monthly_fee']);
    }
    return (float)$debt;
}

//Calculate late months for a student

function late_months_calc($student, $db) {
    $reg_date = new DateTime($student['registration_date']);
    $today = new DateTime();
    
    // Total months including current
    $interval = $reg_date->diff($today);
    $months = ($interval->y * 12) + $interval->m + 1;

    // Total payments made (count unique months paid)
    $query = $db->prepare("SELECT COUNT(DISTINCT DATE_FORMAT(payment_date, '%Y-%m')) FROM payments WHERE student_id = ?");
    $query->execute([$student['id']]);
    $paid = (int)$query->fetchColumn();

    return max(0, $months - $paid);
}


// Monthly Revenue Chart Data (Last 6 Months)

function chart_data($db) {
    $names = [];
    $numbers = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $date = date('Y-m', strtotime("-$i months"));
        $names[] = date('M Y', strtotime("-$i months"));
        
        $query = $db->prepare("
            SELECT SUM(amount) 
            FROM payments 
            WHERE payment_date LIKE ?
        ");
        $query->execute(["$date%"]);
        $numbers[] = (float)($query->fetchColumn() ?: 0);
    }
    
    return [
        'labels' => $names,
        'values' => $numbers
    ];
}

// Student Payment Status

function status_data($db) {
    $month = date('m');
    $year = date('Y');
    
    // Total Active Students
    $query = $db->query("SELECT COUNT(*) FROM students");
    $total_active = (int)$query->fetchColumn();
    
    // Students who paid this month
    $query = $db->prepare("
        SELECT COUNT(DISTINCT student_id) 
        FROM payments 
        WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?
    ");
    $query->execute([$month, $year]);
    $paid_count = (int)$query->fetchColumn();
    
    $unpaid_count = max(0, $total_active - $paid_count);
    
    // Default if no data
    if ($total_active === 0) {
        return [
            'labels' => ['No Students'],
            'values' => [0, 0]
        ];
    }

    return [
        'labels' => ['Paid Students', 'Unpaid Students'],
        'values' => [$paid_count, $unpaid_count]
    ];
}

/**
 * RESTORED FEATURE: Payment Method Distribution
 */
function pay_method_data($db) {
    $query = $db->query("
        SELECT payment_method, SUM(amount) as total 
        FROM payments 
        GROUP BY payment_method 
        ORDER BY total DESC
    ");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $names = [];
    $numbers = [];
    
    if (empty($results)) {
        return [
            'labels' => ['No Data'],
            'values' => [0]
        ];
    }

    foreach ($results as $row) {
        $names[] = ucfirst($row['payment_method'] ?: 'Other');
        $numbers[] = (float)$row['total'];
    }
    
    return [
        'labels' => $names,
        'values' => $numbers
    ];
}
