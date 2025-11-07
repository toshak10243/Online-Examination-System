<?php
require_once __DIR__ . '/../dbConnection.php';

$stmt = $pdo->query("SELECT e.*, s.subject_name FROM exams e JOIN subjects s ON e.subject_id = s.subject_id ORDER BY e.created_at DESC");
$rows = $stmt->fetchAll();

ob_start(); ?>
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>Exam Title</th>
      <th>Subject</th>
      <th>Start</th>
      <th>End</th>
      <th>Total Qs</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php if (empty($rows)): ?>
    <tr><td colspan="7" class="text-center text-muted py-3">No exams found.</td></tr>
  <?php else: foreach ($rows as $r):
    $now = new DateTime();
    $start = new DateTime($r['start_time']);
    $end = new DateTime($r['end_time']);
    if ($now < $start) $status = "<span class='badge bg-info'>Upcoming</span>";
    elseif ($now > $end) $status = "<span class='badge bg-secondary'>Expired</span>";
    else $status = "<span class='badge bg-success'>Active</span>";
  ?>
    <tr>
      <td><strong><?=htmlspecialchars($r['exam_title'])?></strong></td>
      <td><?=htmlspecialchars($r['subject_name'])?></td>
      <td><?=date('d M Y H:i', strtotime($r['start_time']))?></td>
      <td><?=date('d M Y H:i', strtotime($r['end_time']))?></td>
      <td><?=$r['total_questions']?></td>
      <td><?=$status?></td>
      <td>
        <button class="btn btn-sm btn-outline-primary editExamBtn" data-id="<?=$r['exam_id']?>">Edit</button>
        <button class="btn btn-sm btn-outline-danger deleteExamBtn" data-id="<?=$r['exam_id']?>">Delete</button>
      </td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php echo ob_get_clean(); ?>
