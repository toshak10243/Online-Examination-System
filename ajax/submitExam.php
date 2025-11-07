<?php
require_once '../dbConnection.php';
header('Content-Type: application/json');

$attempt_id = (int)($_POST['attempt_id'] ?? 0);
$isForced = isset($_POST['forced']) && $_POST['forced'] == 1; // For cheating or exit fullscreen
$scoreOverride = isset($_POST['score']) ? (int)$_POST['score'] : null;

if (!$attempt_id) {
  echo json_encode(['status'=>'error','message'=>'Invalid attempt ID']); 
  exit;
}

try {
  if ($isForced) {
    // 🚨 Forced auto-submit due to violation (0 marks or given score)
    $forcedScore = $scoreOverride ?? 0;
    $upd = $pdo->prepare("UPDATE exam_attempts 
                          SET score=?, submitted=1, end_time=NOW() 
                          WHERE attempt_id=?");
    $upd->execute([$forcedScore, $attempt_id]);

    echo json_encode([
      'status'=>'error',
      'message'=>"Exam auto-submitted (Score: $forcedScore) due to rule violation."
    ]);
    exit;
  }

  // ✅ Normal submission path
  $stmt = $pdo->prepare("SELECT a.question_id,a.chosen_option,q.correct_option 
                         FROM exam_answers a
                         JOIN questions q ON a.question_id=q.question_id 
                         WHERE a.attempt_id=?");
  $stmt->execute([$attempt_id]);
  
  $score = 0;
  foreach($stmt->fetchAll() as $r){
    if ($r['chosen_option'] && $r['chosen_option'] === $r['correct_option']) {
      $score++;
    }
  }

  $upd = $pdo->prepare("UPDATE exam_attempts 
                        SET score=?, submitted=1, end_time=NOW() 
                        WHERE attempt_id=?");
  $upd->execute([$score, $attempt_id]);

  echo json_encode(['status'=>'success','message'=>"Score saved successfully: $score"]);

} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
