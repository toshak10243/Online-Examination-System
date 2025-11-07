<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student'){ header('Location: login.php'); exit; }
$exam_id = (int)($_GET['exam_id'] ?? 0);
$stmt = $pdo->prepare("SELECT e.*, s.subject_name FROM exams e JOIN subjects s ON e.subject_id=s.subject_id WHERE exam_id=?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch();
if(!$exam){ die('Exam not found'); }
include 'includes/header.php';
?>
<div class="card p-4">
  <h4><?=htmlspecialchars($exam['exam_title'])?> – <?=htmlspecialchars($exam['subject_name'])?></h4>
  <ul>
      <li>You can attempt this exam only once.</li>
      <li>Follow the time limit as per the schedule.</li>
      <li>Do not switch tabs or minimize the browser.</li>
      <li>Exiting full screen will auto-submit your exam with 0 marks.</li>
      <li>Stay focused. Every question carries equal marks.</li>
  </ul>
  <form action="start_exam.php" method="post">
    <input type="hidden" name="exam_id" value="<?=$exam_id?>">
    <button class="btn btn-success">I’m Ready, Start Exam</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
