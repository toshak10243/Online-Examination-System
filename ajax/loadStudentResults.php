<?php
require_once __DIR__.'/../dbConnection.php';
session_start();
$student_id = $_SESSION['user']['user_id'];

$sql = "SELECT ea.*, e.exam_title, s.subject_name
        FROM exam_attempts ea
        JOIN exams e ON ea.exam_id = e.exam_id
        JOIN subjects s ON e.subject_id = s.subject_id
        WHERE ea.student_id=?
        ORDER BY ea.start_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$rows = $stmt->fetchAll();

ob_start(); ?>
<table class="table table-striped align-middle">
  <thead class="table-light">
    <tr>
      <th>Exam</th>
      <th>Subject</th>
      <th>Score</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
  <?php if(empty($rows)): ?>
    <tr><td colspan="4" class="text-center text-muted py-3">No results yet.</td></tr>
  <?php else:
    foreach($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['exam_title'])?></td>
      <td><span class="badge bg-primary"><?=htmlspecialchars($r['subject_name'])?></span></td>
      <td><strong><?=$r['score']?></strong></td>
      <td><?= $r['end_time'] ? date('d M Y H:i', strtotime($r['end_time'])) : '<span class="text-danger">Terminated</span>' ?></td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php echo ob_get_clean();
