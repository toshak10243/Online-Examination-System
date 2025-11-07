<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student'){
  header('Location: login.php'); exit;
}
include 'includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-6">
    <h4 class="fw-bold mb-3">🏆 Overall Top Scorers</h4>
    <div id="overallLeaderboard">Loading...</div>
  </div>
  <div class="col-md-6">
    <h4 class="fw-bold mb-3">📘 Top Scorers by Subject</h4>
    <div id="subjectLeaderboard">Loading...</div>
  </div>
</div>

<script>
function loadLeaderboards(){
  $('#overallLeaderboard').html('<div class="text-center py-3 text-muted">Loading...</div>');
  $('#subjectLeaderboard').html('<div class="text-center py-3 text-muted">Loading...</div>');

  $.get('ajax/loadLeaderboards.php', function(html){
    const res = JSON.parse(html);
    $('#overallLeaderboard').html(res.overall);
    $('#subjectLeaderboard').html(res.subjects);
  });
}
loadLeaderboards();
</script>

<?php include 'includes/footer.php'; ?>
