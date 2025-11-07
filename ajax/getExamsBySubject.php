<?php
require_once __DIR__.'/../dbConnection.php';
$subject_id = (int)($_GET['subject_id'] ?? 0);
$stmt = $pdo->prepare("SELECT exam_id, exam_title FROM exams WHERE subject_id=? ORDER BY exam_title");
$stmt->execute([$subject_id]);
echo json_encode($stmt->fetchAll());
