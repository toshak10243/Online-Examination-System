<?php
require_once 'dbConnection.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student') {
  header('Location: login.php'); exit;
}
include 'includes/header.php';

$student_id = $_SESSION['user']['user_id'];
?>
<div class="card p-4 shadow-sm">
  <h4 class="mb-3">My Results</h4>
  <div id="resultsContainer">Loading...</div>
</div>

<script>
function loadResults(){
  $('#resultsContainer').html('<div class="text-center py-3 text-muted">Loading...</div>');
  $.get('ajax/loadStudentResults.php', function(html){
    $('#resultsContainer').html(html);
  });
}
loadResults();
</script>
<?php include 'includes/footer.php'; ?>
