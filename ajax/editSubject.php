<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');
$id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
if (!$id) { echo json_encode(['status'=>'error','message'=>'Invalid id']); exit; }
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE subject_id = ?");
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) echo json_encode(['status'=>'error','message'=>'Not found']);
else echo json_encode(['status'=>'success','data'=>$r]);
