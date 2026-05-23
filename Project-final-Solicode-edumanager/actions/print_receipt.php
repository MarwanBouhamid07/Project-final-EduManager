<?php
ob_start(); 
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$payment_id = $_GET['id'] ;


try {
    $query = $db->prepare("
        SELECT p.*, s.full_name, s.grade, s.phone
        FROM payments p 
        JOIN students s ON p.student_id = s.id 
        WHERE p.id = ?
    ");
    $query->execute([$payment_id]);
    $payment = $query->fetch(PDO::FETCH_ASSOC);

    // 2. Check if the database actually found the record
    if (!$payment) {
        die("Error " . $payment_id);
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 3. Prepare variables (only if payment exists)
$_amount = number_format($payment['amount'], 2) . ' MAD';
$_date = date('F d, Y', strtotime($payment['payment_date']));
$_receipt_no = $payment['receipt_number'];
$_student_name = $payment['full_name'];
$_grade = $payment['grade'];
$_method = ucfirst($payment['payment_method']);



// HTML Template
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Receipt - {$_receipt_no}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .receipt-container {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #2563eb;
            padding: 40px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 16px;
        }
        .details-section {
            margin-bottom: 40px;
        }
        .details-section table {
            width: 100%;
        }
        .details-section td {
            padding: 8px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #4b5563;
            display: inline-block;
            width: 130px;
        }
        .table-payment {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .table-payment th, .table-payment td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
        }
        .table-payment th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .text-right {
            text-align: right !important;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 14px;
            color: #4b5563;
        }
        .signature-area {
            margin-top: 60px;
            display: inline-block;
            border-top: 1px solid #9ca3af;
            padding-top: 10px;
            width: 250px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class='receipt-container'>
    <div class='header'>
        <h1>EduManager Center</h1>
        <p>Invoice / Receipt</p>
    </div>

    <div class='details-section'>
        <table>
            <tr>
                <td style='width: 50%;'>
                    <div><span class='info-label'>Transaction ID:</span> {$_receipt_no}</div>
                    <div><span class='info-label'>Payment Date:</span> {$_date}</div>
                    <div><span class='info-label'>Payment Method:</span> {$_method}</div>
                </td>
                <td style='width: 50%;'>
                    <div><span class='info-label'>Receipt To:</span></div>
                    <div><strong>{$_student_name}</strong></div>
                    <div>Grade: {$_grade}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class='table-payment'>
        <thead>
            <tr>
                <th>Description</th>
                <th class='text-right'>Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monthly Tuition Fee - {$_grade}</td>
                <td class='text-right'>{$_amount}</td>
            </tr>
            <tr class='total-row'>
                <td class='text-right'>Total Paid:</td>
                <td class='text-right'>{$_amount}</td>
            </tr>
        </tbody>
    </table>

    <div class='footer'>
        <p>This is a system generated invoice/receipt.</p>
        <p>Thank you for your business!</p>
        <div class='signature-area'>
            Authorized Signature
        </div>
    </div>
</div>

</body>
</html>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();

ob_end_clean(); 
$dompdf->stream("receipt.pdf");
exit(); 


