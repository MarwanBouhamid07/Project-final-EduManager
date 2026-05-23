<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';
    if (!check_token($token)) {
        go_with_message("../pages/students.php", "error in securty.", "error");
    }


    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $start_month = $_POST['start_month'];
    $months_count = (int) $_POST['months_count'];

    try {


        $sql = "INSERT INTO payments (student_id, amount,payment_month, payment_date, payment_method, receipt_number) 
                VALUES (:student_id, :amount,:payment_month, :payment_date, :payment_method, :receipt_number)";

        $query = $db->prepare($sql);

        for ($i = 0; $i < $months_count; $i++) {
            $receipt_number = make_receipt($db);

            $current_target_month = date('Y-m', strtotime("+$i month", strtotime($start_month . "-01")));

            $query->execute([
                ':student_id' => $student_id,
                ':amount' => $amount,
                ':payment_month' => $current_target_month,
                ':payment_date' => $payment_date,
                ':payment_method' => $payment_method,
                ':receipt_number' => $receipt_number
            ]);
        }

        go_with_message('../pages/payments.php', 'Payment recorded successfully.', 'success');

    } catch (PDOException $e) {
        go_with_message('../pages/add_payment.php', "Error: " . $e->getMessage(), 'error');
    }

}