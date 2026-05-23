<?php

// Get Total Students Count

function total_students($db) {
    $query = $db->query("SELECT COUNT(*) FROM students");
    return $query->fetchColumn();
}




// Get Recent Payments

function recent_payments($db, $limit = 5) {
    $query = $db->prepare("
        SELECT p.*, s.full_name, s.grade 
        FROM payments p 
        JOIN students s ON p.student_id = s.id 
        ORDER BY p.payment_date DESC, p.created_at DESC 
        LIMIT :limit
    ");
    $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);// i don't use excute because it is send values like string
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
