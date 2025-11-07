<?php
require_once __DIR__ . '/../dbConnection.php';
$stmt = $pdo->query("SELECT * FROM subjects ORDER BY subject_name");
$rows = $stmt->fetchAll();
ob_start();
?>
<table class="table table-hover">
  <thead><tr><th>Subject</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    <?php if(empty($rows)): ?>
      <tr><td colspan="3" class="text-center">No subjects found.</td></tr>
    <?php else: foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['subject_name'])?></td>
        <td><?=htmlspecialchars($r['created_at'])?></td>
        <td>
          <button class="btn btn-sm btn-outline-primary editSubjectBtn" data-id="<?= $r['subject_id'] ?>">Edit</button>
          <button class="btn btn-sm btn-outline-danger deleteSubjectBtn" data-id="<?= $r['subject_id'] ?>">Delete</button>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>
<?php
echo ob_get_clean();
