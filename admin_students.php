<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
  header('Location: login.php'); exit;
}
include 'includes/header.php';
?>
<div class="card p-4 shadow-sm">
  <h4 class="mb-3">Manage Students</h4>
  <form id="addStudentForm" class="row g-3 mb-4">
    <div class="col-md-4">
      <input name="fullname" class="form-control" placeholder="Full Name" required>
    </div>
    <div class="col-md-3">
      <input name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="col-md-3">
      <input name="password" type="password" class="form-control" placeholder="Password" required>
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primary">Add Student</button>
    </div>
  </form>

  <div id="studentsContainer">Loading...</div>
</div>

<script>
// Load students
function loadStudents(){
  $('#studentsContainer').html('<div class="text-center text-muted py-3">Loading...</div>');
  $.get('ajax/loadStudents.php', function(html){
    $('#studentsContainer').html(html);
  });
}
loadStudents();

// Add student via AJAX
$('#addStudentForm').on('submit', function(e){
  e.preventDefault();
  $.post('ajax/addStudent.php', $(this).serialize(), function(res){
    if(res.status==='success'){
      Swal.fire({icon:'success', title:res.message, toast:true, timer:1500, showConfirmButton:false});
      $('#addStudentForm')[0].reset();
      loadStudents();
    } else Swal.fire('Error', res.message, 'error');
  }, 'json');
});

// Delete student
$(document).on('click','.deleteStudentBtn', function(){
  const id = $(this).data('id');
  Swal.fire({title:'Delete this student?', icon:'warning', showCancelButton:true}).then(r=>{
    if(r.isConfirmed){
      $.post('ajax/deleteStudent.php',{user_id:id}, function(res){
        if(res.status==='success'){
          loadStudents();
          Swal.fire({icon:'success', title:res.message, toast:true, timer:1500, showConfirmButton:false});
        } else Swal.fire('Error', res.message, 'error');
      },'json');
    }
  });
});
</script>
<?php include 'includes/footer.php'; ?>
