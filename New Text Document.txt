<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
  header('Location: login.php'); exit;
}
include 'includes/header.php';
?>
<div class="card p-4 shadow-sm">
  <h4 class="mb-3">Exam Results</h4>
  <div class="row g-2 mb-3">
    <div class="col-md-4">
      <select id="filterSubject" class="form-select">
        <option value="">All Subjects</option>
        <?php
          $subs = $pdo->query("SELECT * FROM subjects ORDER BY subject_name")->fetchAll();
          foreach($subs as $s) echo "<option value='{$s['subject_id']}'>{$s['subject_name']}</option>";
        ?>
      </select>
    </div>
    <div class="col-md-4">
      <select id="filterExam" class="form-select"><option value="">All Exams</option></select>
    </div>
  </div>
  <div id="adminResultsContainer">Loading...</div>
</div>

<script>
function loadAdminResults(){
  const sid = $('#filterSubject').val();
  const eid = $('#filterExam').val();
  $('#adminResultsContainer').html('<div class="text-center py-3 text-muted">Loading...</div>');
  $.get('ajax/loadAdminResults.php', {subject_id:sid, exam_id:eid}, function(html){
    $('#adminResultsContainer').html(html);
  });
}
$('#filterSubject').change(function(){
  const sid = $(this).val();
  $.getJSON('ajax/getExamsBySubject.php', {subject_id:sid}, function(list){
    $('#filterExam').html('<option value="">All Exams</option>');
    list.forEach(e=>$('#filterExam').append(`<option value="${e.exam_id}">${e.exam_title}</option>`));
    loadAdminResults();
  });
});
$('#filterExam').change(loadAdminResults);
loadAdminResults();
</script>
<?php include 'includes/footer.php'; ?>
