<?php
require_once 'dbConnection.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php'); exit;
}
include 'includes/header.php';
?>
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Create Exam</h5>
  </div>

<form id="examForm" novalidate>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Exam Title</label>
      <input name="exam_title" id="exam_title" class="form-control" placeholder="Enter exam title" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Subject</label>
      <select name="subject_id" id="exam_subject" class="form-select" required>
        <option value="">-- Select Subject --</option>
        <?php
          $stmt = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
          foreach($stmt->fetchAll() as $s)
            echo "<option value=\"{$s['subject_id']}\">".htmlspecialchars($s['subject_name'])."</option>";
        ?>
      </select>
    </div>

    <!-- Start and End time with datetime-local -->
   <div class="col-md-6">
  <label class="form-label">Start Time</label>
  <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
</div>

   <div class="col-md-6">
  <label class="form-label">End Time</label>
  <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
</div>

    <div class="col-md-4">
      <label class="form-label">Total Questions</label>
      <input type="number" name="total_questions" id="total_questions" class="form-control" min="1" placeholder="e.g. 10" required>
    </div>

    <div class="col-12">
      <button class="btn btn-primary" id="saveExamBtn" type="submit">Save Exam</button>
    </div>
  </div>
</form>

  </form>

  <hr>
  <div id="examsContainer">Loading created exams...</div>
</div>


<?php include 'includes/footer.php'; ?>
