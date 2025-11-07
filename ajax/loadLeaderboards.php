<?php
require_once __DIR__.'/../dbConnection.php';

/* Overall Leaderboard (Top 10 by Average Score) */
$sql = "SELECT u.fullname, ROUND(AVG(ea.score),1) AS avg_score
        FROM exam_attempts ea
        JOIN users u ON ea.student_id=u.user_id
        WHERE ea.submitted=1
        GROUP BY u.user_id
        ORDER BY avg_score DESC
        LIMIT 10";
$overall = $pdo->query($sql)->fetchAll();

/* Per-Subject Leaderboard (Top 3 per Subject) */
$sql2 = "SELECT s.subject_name, u.fullname, ea.score
         FROM exam_attempts ea
         JOIN exams e ON ea.exam_id=e.exam_id
         JOIN subjects s ON e.subject_id=s.subject_id
         JOIN users u ON ea.student_id=u.user_id
         WHERE ea.submitted=1
         ORDER BY s.subject_name, ea.score DESC";
$data = $pdo->query($sql2)->fetchAll();

/* Format HTML for overall */
ob_start(); ?>
<table class="table table-hover table-sm align-middle">
  <thead class="table-light"><tr><th>Rank</th><th>Student</th><th>Avg Score</th></tr></thead>
  <tbody>
  <?php if(empty($overall)): ?>
    <tr><td colspan="3" class="text-center text-muted">No data yet.</td></tr>
  <?php else:
    $rank=1;
    foreach($overall as $r): ?>
    <tr>
      <td><span class="badge bg-primary"><?=$rank++?></span></td>
      <td><?=htmlspecialchars($r['fullname'])?></td>
      <td><?=$r['avg_score']?></td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php $overallHtml = ob_get_clean();

/* Format HTML for per-subject leaderboard */
$subjectHtml = '';
$grouped = [];
foreach($data as $d){
  $grouped[$d['subject_name']][] = $d;
}
foreach($grouped as $subject=>$rows){
  $subjectHtml .= "<h6 class='fw-bold mt-3'>$subject</h6>";
  $subjectHtml .= "<table class='table table-bordered table-sm'><tbody>";
  $rank=1;
  foreach(array_slice($rows,0,3) as $r){
    $subjectHtml .= "<tr>
      <td><span class='badge bg-success'>$rank</span></td>
      <td>".htmlspecialchars($r['fullname'])."</td>
      <td>".$r['score']."</td>
    </tr>";
    $rank++;
  }
  $subjectHtml .= "</tbody></table>";
}

echo json_encode(['overall'=>$overallHtml,'subjects'=>$subjectHtml]);
