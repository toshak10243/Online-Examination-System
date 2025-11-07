<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$id = (int)($_POST['user_id'] ?? 0);
if(!$id){ echo json_encode(['status'=>'error','message'=>'Invalid ID']); exit; }

try {
  $stmt = $pdo->prepare("DELETE FROM users WHERE user_id=? AND role='student'");
  $stmt->execute([$id]);
  echo json_encode(['status'=>'success','message'=>'Student deleted successfully']);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
