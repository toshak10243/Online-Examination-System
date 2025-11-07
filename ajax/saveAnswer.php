<?php
require_once '../dbConnection.php';
$attempt_id=(int)($_POST['attempt_id']??0);
$qid=(int)($_POST['qid']??0);
$opt=$_POST['option']??'';
if(!in_array($opt,['A','B','C','D'])) exit;
$stmt=$pdo->prepare("UPDATE exam_answers SET chosen_option=? WHERE attempt_id=? AND question_id=?");
$stmt->execute([$opt,$attempt_id,$qid]);
