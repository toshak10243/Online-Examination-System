<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch exam details for edit modal
    $exam_id = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;
    if (!$exam_id) {
        echo json_encode(['status'=>'error','message'=>'Invalid exam ID']); exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM exams WHERE exam_id=?");
    $stmt->execute([$exam_id]);
    $exam = $stmt->fetch();
    if (!$exam) {
        echo json_encode(['status'=>'error','message'=>'Exam not found']); exit;
    }
    echo json_encode(['status'=>'success','data'=>$exam]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update exam details
    $exam_id = (int)($_POST['exam_id'] ?? 0);
    $exam_title = trim($_POST['exam_title'] ?? '');
    $subject_id = (int)($_POST['subject_id'] ?? 0);
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $total_questions = (int)($_POST['total_questions'] ?? 0);

    if (!$exam_id || $exam_title==='' || !$subject_id || $start_time==='' || $end_time==='' || $total_questions<=0) {
        echo json_encode(['status'=>'error','message'=>'Please fill all fields']);
        exit;
    }

    try {
        $upd = $pdo->prepare("UPDATE exams SET exam_title=?, subject_id=?, start_time=?, end_time=?, total_questions=? WHERE exam_id=?");
        $upd->execute([$exam_title, $subject_id, $start_time, $end_time, $total_questions, $exam_id]);
        echo json_encode(['status'=>'success','message'=>'Exam updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
    }
}
