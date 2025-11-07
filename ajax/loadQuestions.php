<?php
require_once __DIR__ . '/../dbConnection.php';
$subject_id = isset($_GET['subject_id']) && $_GET['subject_id'] !== '' ? (int)$_GET['subject_id'] : null;

$where = '';
$params = [];
if ($subject_id) { $where = 'WHERE q.subject_id = ?'; $params[] = $subject_id; }

$sql = "SELECT q.*, s.subject_name FROM questions q JOIN subjects s ON q.subject_id = s.subject_id $where ORDER BY q.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

ob_start();
?>
<table class="table table-hover">
  <thead><tr><th>Subject</th><th>Question</th><th>Correct</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="5" class="text-center">No questions found.</td></tr>
    <?php else: foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['subject_name'])?></td>
        <td><?=htmlspecialchars($r['question_text'])?></td>
        <td><?=htmlspecialchars($r['correct_option'])?></td>
        <td><?=htmlspecialchars($r['created_at'])?></td>
        <td>
          <button class="btn btn-sm btn-outline-primary editQuestionBtn" data-id="<?= $r['question_id'] ?>">Edit</button>
          <button class="btn btn-sm btn-outline-danger deleteQuestionBtn" data-id="<?= $r['question_id'] ?>">Delete</button>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?php
echo ob_get_clean();
