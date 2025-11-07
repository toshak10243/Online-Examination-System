<?php
require_once '../dbConnection.php';
header('Content-Type: application/json');
$action = $_GET['action'] ?? '';
if($action==='get'){
  $attempt_id=(int)($_GET['attempt_id']??0);
  $qid=(int)($_GET['qid']??0);
  $stmt=$pdo->prepare("SELECT chosen_option FROM exam_answers WHERE attempt_id=? AND question_id=?");
  $stmt->execute([$attempt_id,$qid]);
  $r=$stmt->fetch();
  echo json_encode(['option'=>$r['chosen_option']??null]);
}
