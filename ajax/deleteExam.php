<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$exam_id = isset($_POST['exam_id']) ? (int)$_POST['exam_id'] : 0;

if (!$exam_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
    exit;
}

try {
    // Delete exam and all related attempts/answers cascade automatically (foreign keys)
    $stmt = $pdo->prepare("DELETE FROM exams WHERE exam_id = ?");
    $stmt->execute([$exam_id]);
    echo json_encode(['status' => 'success', 'message' => 'Exam deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: '.$e->getMessage()]);
}
