<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$subject_id = isset($_POST['subject_id']) && $_POST['subject_id'] !== '' ? (int)$_POST['subject_id'] : null;
$name = trim($_POST['subject_name'] ?? '');
if ($name === '') { echo json_encode(['status'=>'error','message'=>'Enter subject name']); exit; }

try {
    if ($subject_id) {
        $stmt = $pdo->prepare("UPDATE subjects SET subject_name = ? WHERE subject_id = ?");
        $stmt->execute([$name, $subject_id]);
        echo json_encode(['status'=>'success','message'=>'Subject updated']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(['status'=>'success','message'=>'Subject added']);
    }
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
