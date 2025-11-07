<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');
$id = isset($_GET['question_id']) ? (int)$_GET['question_id'] : 0;
if (!$id) { echo json_encode(['status'=>'error','message'=>'Invalid id']); exit; }
$stmt = $pdo->prepare("SELECT * FROM questions WHERE question_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) echo json_encode(['status'=>'error','message'=>'Not found']);
else echo json_encode(['status'=>'success','data'=>$r]);
