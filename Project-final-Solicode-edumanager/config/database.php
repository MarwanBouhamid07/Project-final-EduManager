<?php


$host = 'localhost'; 
$db_name = 'database_student_payment_system';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name;", $username, $password);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
