<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';
    check_token($token);

    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $start_month = $_POST['start_month'];
    $months_count = (int) $_POST['months_count'];



    try {


        $sql = "UPDATE payments 
                SET amount = :amount, 
                payment_month = :payment_month,
                payment_date = :payment_date, 
                payment_method = :payment_method 
                WHERE id = :id";

        $query = $db->prepare($sql);
        for ($i = 0; $i < $months_count; $i++) {
            
            $current_target_month = date('Y-m', strtotime("+$i month", strtotime($start_month . "-01")));
            
            $query->execute([
                ':amount' => $amount,
                ':payment_month'=> $current_target_month,
                ':payment_date' => $payment_date,
                ':payment_method' => $payment_method,
                ':id' => $id
            ]);
        }

        go_with_message('../pages/payments.php', 'Payment updated successfully.', 'success');

    } catch (PDOException $e) {
        go_with_message("../pages/edit_payment.php?id=$id", "Error: " . $e->getMessage(), 'error');
    }

}
