<?php
require_once __DIR__ . '/../dbConnection.php';
$stmt = $pdo->query("SELECT user_id, fullname, username, created_at FROM users WHERE role='student' ORDER BY created_at DESC");
$rows = $stmt->fetchAll();

ob_start(); ?>
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Full Name</th>
      <th>Username</th>
      <th>Created</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php if(empty($rows)): ?>
    <tr><td colspan="5" class="text-center text-muted py-3">No students found.</td></tr>
  <?php else: foreach($rows as $i=>$r): ?>
    <tr>
      <td><?=$i+1?></td>
      <td><?=htmlspecialchars($r['fullname'])?></td>
      <td><?=htmlspecialchars($r['username'])?></td>
      <td><?=date('d M Y', strtotime($r['created_at']))?></td>
      <td><button class="btn btn-sm btn-outline-danger deleteStudentBtn" data-id="<?=$r['user_id']?>">Delete</button></td>
    </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php echo ob_get_clean();
