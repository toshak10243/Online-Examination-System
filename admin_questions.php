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
    <h5>Questions</h5>
    <button class="btn btn-primary" id="openAddQuestion">Add Question</button>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <select id="filter_subject" class="form-select"><option value="">-- All Subjects --</option>
        <?php
          $stmt = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
          foreach($stmt->fetchAll() as $s) {
            echo "<option value=\"{$s['subject_id']}\">".htmlspecialchars($s['subject_name'])."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary" id="filterQuestionsBtn">Filter</button>
    </div>
  </div>

  <div id="questionsContainer">Loading questions...</div>
</div>

<!-- Add/Edit Question Modal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="questionForm">
        <div class="modal-header">
          <h5 class="modal-title" id="questionModalTitle">Add Question</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="question_id" name="question_id">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Subject</label>
              <select id="question_subject" name="subject_id" class="form-select" required>
                <option value="">-- Select Subject --</option>
                <?php
                  $stmt = $pdo->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name");
                  foreach($stmt->fetchAll() as $s) {
                    echo "<option value=\"{$s['subject_id']}\">".htmlspecialchars($s['subject_name'])."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Correct Option</label>
              <select id="correct_option" name="correct_option" class="form-select" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Question Text</label>
              <textarea id="question_text" name="question_text" class="form-control" rows="3" required></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Option A</label>
              <input name="option_a" id="option_a" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Option B</label>
              <input name="option_b" id="option_b" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Option C</label>
              <input name="option_c" id="option_c" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Option D</label>
              <input name="option_d" id="option_d" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-primary" id="saveQuestionBtn" type="submit">Save Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
