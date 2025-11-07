<?php
require_once __DIR__.'/../dbConnection.php';

/* Exam Summary */
$sql = "SELECT e.exam_title, s.subject_name,
        COUNT(ea.attempt_id) AS total,
        ROUND(AVG(ea.score),1) AS avg
        FROM exam_attempts ea
        JOIN exams e ON ea.exam_id=e.exam_id
        JOIN subjects s ON e.subject_id=s.subject_id
        WHERE ea.submitted=1
        GROUP BY e.exam_id";
$summary = $pdo->query($sql)->fetchAll();

/* Chart Data (subject vs avg score) */
$sql2 = "SELECT s.subject_name, ROUND(AVG(ea.score),1) AS avg
         FROM exam_attempts ea
         JOIN exams e ON ea.exam_id=e.exam_id
         JOIN subjects s ON e.subject_id=s.subject_id
         WHERE ea.submitted=1
         GROUP BY s.subject_id";
$chartData = $pdo->query($sql2)->fetchAll();

echo json_encode([
  'summary'=>$summary,
  'chart'=>[
    'labels'=>array_column($chartData,'subject_name'),
    'data'=>array_column($chartData,'avg')
  ]
]);
