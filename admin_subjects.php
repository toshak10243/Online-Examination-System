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
    <h5>Subjects</h5>
    <button class="btn btn-primary" id="openAddSubject">Add Subject</button>
  </div>

  <div id="subjectsContainer">Loading subjects...</div>
</div>

<!-- Add/Edit Subject Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <form id="subjectForm">
        <div class="modal-header">
          <h5 class="modal-title" id="subjectModalTitle">Add Subject</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="subject_id" name="subject_id">
          <div class="mb-2">
            <label class="form-label">Subject Name</label>
            <input type="text" id="subject_name" name="subject_name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" type="button">Close</button>
          <button class="btn btn-primary" id="saveSubjectBtn" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
