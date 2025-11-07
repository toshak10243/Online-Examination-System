<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$question_id = isset($_POST['question_id']) && $_POST['question_id'] !== '' ? (int)$_POST['question_id'] : null;
$subject_id = isset($_POST['subject_id']) ? (int)$_POST['subject_id'] : 0;
$question_text = trim($_POST['question_text'] ?? '');
$option_a = trim($_POST['option_a'] ?? '');
$option_b = trim($_POST['option_b'] ?? '');
$option_c = trim($_POST['option_c'] ?? '');
$option_d = trim($_POST['option_d'] ?? '');
$correct = $_POST['correct_option'] ?? '';

if ($subject_id <= 0 || $question_text === '' || $option_a === '' || $option_b === '' || $option_c === '' || $option_d === '' || !in_array($correct, ['A','B','C','D'])) {
    echo json_encode(['status'=>'error','message'=>'Please fill all fields correctly.']); exit;
}

try {
    if ($question_id) {
        $upd = $pdo->prepare("UPDATE questions SET subject_id=?, question_text=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_option=? WHERE question_id=?");
        $upd->execute([$subject_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct, $question_id]);
        echo json_encode(['status'=>'success','message'=>'Question updated']);
    } else {
        $ins = $pdo->prepare("INSERT INTO questions (subject_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?,?,?,?,?,?,?)");
        $ins->execute([$subject_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct]);
        echo json_encode(['status'=>'success','message'=>'Question added']);
    }
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
