<?php


// Removes whitespace and converts special characters to HTML entities

function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

// Format Money

function show_money($amount)
{
    return 'MAD ' . number_format($amount, 2);
}

//Check Login

function check_login()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php");
        exit();
    }
}

// Generate CSRF Token

function make_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF Token

function check_token($token)
{
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Redirect with Message

function go_with_message($url, $message, $type = 'success')
{
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}


//Generate Receipt Number

function make_receipt($db)
{
    try {
        $query = $db->query("SELECT COUNT(*) FROM payments");
        $count = $query->fetchColumn();
        return 'RCP-' . date('Ym') . '-' . sprintf('%04d', $count + 1);
    } catch (PDOException $e) {
        return 'RCP-' . date('YmdHis'); // Fallback
    }
}

// Get Student Payment Status

function get_status($student_id, $db)
{
    try {
        $query = $db->prepare("SELECT billing_day, monthly_fee FROM students WHERE id = ?");
        $query->execute([$student_id]);
        $student = $query->fetch();


        $bill_day = (int) $student['billing_day'];
        $month = date('Y-m');
        $today = date('Y-m-d');
        $day = (int) date('d');

        // 1. Check if paid this month
        $query = $db->prepare("SELECT COUNT(*) FROM payments WHERE student_id = ? AND payment_date LIKE ?");
        $query->execute([$student_id, "$month%"]);
        if ($query->fetchColumn() > 0) {
            return 'paid';
        }

        // 2. Determine due date for current month
        $billing_day_clamped = min($bill_day, 28);
        $due = date("Y-m-") . sprintf("%02d", $billing_day_clamped);
        if ($day > $billing_day_clamped) {
            $due_date = date("Y-m-d", strtotime("+1 month", strtotime($due)));
        } else {
            $due_date = $due;
        }

        // 3. Status logic
        if ($today > $due_date) {
            return 'late';
        }

        // Check for 'due soon' (within 3 days of billing day)
        $diff = $billing_day_clamped - $day;
        if ($diff >= 0 && $diff <= 3) {
            return 'due soon';
        }

        return 'unpaid';
    } catch (Exception $e) {
        return 'error'. $e->getMessage();
    }
}

//Calculate Late Months

function late_months($student_id, $db)
{
    try {
        $query = $db->prepare("SELECT registration_date, monthly_fee, billing_day FROM students WHERE id = ?");
        $query->execute([$student_id]);
        $student = $query->fetch();

        $reg_date = new DateTime($student['registration_date']);
        $today = new DateTime();

        // Total expected payments since registration
        $interval = $reg_date->diff($today);
        $months = ($interval->y * 12) + $interval->m + 1;
        // Total payments made
        $query = $db->prepare("SELECT COUNT(DISTINCT DATE_FORMAT(payment_date, '%Y-%m')) FROM payments WHERE student_id = ?");
        $query->execute([$student_id]);
        $paid = (int) $query->fetchColumn();

        $late_months = $months - $paid;

        // If it's early in the month (before billing day) and they haven't paid, don't count current month as "late" yet
        $day = (int) date('d');
        if ($day <= (int) $student['billing_day']) {
            // Check if they paid this month
            $query = $db->prepare("SELECT COUNT(*) FROM payments WHERE student_id = ? AND payment_date LIKE ?");
            $query->execute([$student_id, date('Y-m') . '%']);
            if ($query->fetchColumn() == 0) {
                $late_months = max(0, $late_months - 1);
            }
        }

        return max(0, $late_months);
    } catch (Exception $e) {
        return 0;
    }
}

?>