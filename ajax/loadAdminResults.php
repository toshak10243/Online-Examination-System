<?php
require_once __DIR__.'/../dbConnection.php';
$subject_id = $_GET['subject_id'] ?? '';
$exam_id    = $_GET['exam_id'] ?? '';

$where = [];
$params = [];
if($subject_id!=''){
  $where[] = 's.subject_id=?';
  $params[] = $subject_id;
}
if($exam_id!=''){
  $where[] = 'e.exam_id=?';
  $params[] = $exam_id;
}
$whereSQL = $where ? 'WHERE '.implode(' AND ',$where) : '';

$sql = "SELECT ea.*, u.username, e.exam_title, s.subject_name
        FROM exam_attempts ea
        JOIN users u ON ea.student_id=u.user_id
        JOIN exams e ON ea.exam_id=e.exam_id
        JOIN subjects s ON e.subject_id=s.subject_id
        $whereSQL
        ORDER BY ea.score DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

ob_start(); ?>
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>Student</th>
      <th>Exam</th>
      <th>Subject</th>
      <th>Score</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
  <?php if(empty($rows)): ?>
    <tr><td colspan="5" class="text-center text-muted py-3">No results found.</td></tr>
  <?php else: foreach($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['username'])?></td>
      <td><?=htmlspecialchars($r['exam_title'])?></td>
      <td><?=htmlspecialchars($r['subject_name'])?></td>
      <td><strong><?=$r['score']?></strong></td>
      <td><?= $r['end_time'] ? date('d M Y H:i', strtotime($r['end_time'])) : '<span class="text-danger">Terminated</span>' ?></td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php echo ob_get_clean();
