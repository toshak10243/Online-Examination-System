<?php
require_once 'dbConnection.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
  header('Location: login.php'); exit;
}
include 'includes/header.php';
$student_id = $_SESSION['user']['user_id'];

$sql = "SELECT e.*, s.subject_name,
        (SELECT COUNT(*) FROM exam_attempts a WHERE a.exam_id=e.exam_id AND a.student_id=?) AS attempted
        FROM exams e JOIN subjects s ON e.subject_id=s.subject_id
        ORDER BY e.start_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$exams = $stmt->fetchAll();
?>
<div class="row g-4">
  <?php foreach($exams as $ex): 
    $now = new DateTime();
    $start = new DateTime($ex['start_time']);
    $end = new DateTime($ex['end_time']);
    $disabled = ($ex['attempted']>0 || $now<$start || $now>$end);
  ?>
  <div class="col-md-4">
    <div class="card shadow-sm p-3">
      <h5><?=htmlspecialchars($ex['exam_title'])?></h5>
      <span class="badge bg-primary"><?=htmlspecialchars($ex['subject_name'])?></span>
      <p class="mt-2 mb-1 small">Start: <?=date('d M H:i',strtotime($ex['start_time']))?></p>
      <p class="small">End: <?=date('d M H:i',strtotime($ex['end_time']))?></p>
      <?php if($ex['attempted']>0): ?>
        <button class="btn btn-secondary btn-sm w-100" disabled>Already Attempted</button>
      <?php elseif($now<$start): ?>
        <a href="exam_rules.php?exam_id=<?=$ex['exam_id']?>" class="btn btn-success btn-sm w-100">Start Exam (Test)</a>
      <?php elseif($now>$end): ?>
        <button class="btn btn-outline-danger btn-sm w-100" disabled>Expired</button>
      <?php else: ?>
        <a href="exam_rules.php?exam_id=<?=$ex['exam_id']?>" class="btn btn-success btn-sm w-100">Start Exam</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>
