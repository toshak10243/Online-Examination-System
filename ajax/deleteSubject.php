<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');
$id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
if (!$id) { echo json_encode(['status'=>'error','message'=>'Invalid id']); exit; }
try {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status'=>'success','message'=>'Subject deleted']);
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
