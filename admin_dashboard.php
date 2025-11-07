<?php
require_once 'dbConnection.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php'); exit;
}
include 'includes/header.php';
?>
<div class="row g-4">
  <div class="col-md-4">
    <div class="card p-3">
      <h5>Subjects</h5>
      <p><a href="admin_subjects.php" class="btn btn-outline-primary btn-sm">Manage Subjects</a></p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h5>Questions</h5>
      <p><a href="admin_questions.php" class="btn btn-outline-primary btn-sm">Manage Questions</a></p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h5>Exams</h5>
      <p><a href="admin_exams.php" class="btn btn-outline-primary btn-sm">Create Exam</a></p>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
