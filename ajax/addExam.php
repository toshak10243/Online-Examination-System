<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$exam_title = trim($_POST['exam_title'] ?? '');
$subject_id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$total_questions = isset($_POST['total_questions']) ? (int)$_POST['total_questions'] : 0;

if ($exam_title === '' || $subject_id <= 0 || $start_time === '' || $end_time === '' || $total_questions <= 0) {
    echo json_encode(['status'=>'error','message'=>'Please fill all fields']); exit;
}
try {
    $s = new DateTime($start_time);
    $e = new DateTime($end_time);
    if ($s >= $e) { echo json_encode(['status'=>'error','message'=>'End time must be after start']); exit; }
} catch (Exception $ex) {
    echo json_encode(['status'=>'error','message'=>'Invalid date/time']); exit;
}

try {
    $ins = $pdo->prepare("INSERT INTO exams (exam_title, subject_id, start_time, end_time, total_questions) VALUES (?,?,?,?,?)");
    $ins->execute([$exam_title, $subject_id, $start_time, $end_time, $total_questions]);
    echo json_encode(['status'=>'success','message'=>'Exam created']);
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
