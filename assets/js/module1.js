// assets/js/module1.js
$(function(){

  // ---------- Subjects ----------
  function loadSubjects() {
    $('#subjectsContainer').html('<div class="py-3 text-center">Loading...</div>');
    $.get('ajax/loadSubjects.php', function(html){ $('#subjectsContainer').html(html); });
  }
  loadSubjects();

  $('#openAddSubject').on('click', function(){
    $('#subjectForm')[0].reset(); $('#subject_id').val(''); $('#subjectModalTitle').text('Add Subject');
    $('#subjectModal').modal('show');
  });

  $('#subjectForm').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serialize();
    $('#saveSubjectBtn').prop('disabled', true);
    $.post('ajax/addSubject.php', data, function(res){
      if (res.status === 'success') {
        $('#subjectModal').modal('hide'); loadSubjects();
        Swal.fire({icon:'success', title: res.message, toast:true, position:'top-end', timer:1500, showConfirmButton:false});
      } else Swal.fire('Error', res.message, 'error');
    }, 'json').always(function(){ $('#saveSubjectBtn').prop('disabled', false); });
  });

  $(document).on('click', '.deleteSubjectBtn', function(){
    var id = $(this).data('id');
    Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true}).then(ans=>{
      if (ans.isConfirmed) {
        $.post('ajax/deleteSubject.php', {subject_id:id}, function(res){
          if (res.status === 'success') { loadSubjects(); Swal.fire({icon:'success',title:res.message, toast:true, position:'top-end', timer:1400, showConfirmButton:false}); }
          else Swal.fire('Error', res.message,'error');
        }, 'json');
      }
    });
  });

  $(document).on('click', '.editSubjectBtn', function(){
    var id = $(this).data('id');
    $.getJSON('ajax/editSubject.php', {subject_id: id}, function(res){
      if (res.status === 'success') {
        $('#subject_id').val(res.data.subject_id);
        $('#subject_name').val(res.data.subject_name);
        $('#subjectModalTitle').text('Edit Subject');
        $('#subjectModal').modal('show');
      } else Swal.fire('Error', res.message, 'error');
    });
  });

  // ---------- Questions ----------
  function loadQuestions() {
    var subject = $('#filter_subject').val() || '';
    $('#questionsContainer').html('<div class="py-3 text-center">Loading...</div>');
    $.get('ajax/loadQuestions.php', {subject_id: subject}, function(html){ $('#questionsContainer').html(html); });
  }
  loadQuestions();

  $('#filterQuestionsBtn').on('click', function(){ loadQuestions(); });

  $('#openAddQuestion').on('click', function(){
    $('#questionForm')[0].reset(); $('#question_id').val(''); $('#questionModalTitle').text('Add Question');
    $('#questionModal').modal('show');
  });

  $('#questionForm').on('submit', function(e){
    e.preventDefault();
    $('#saveQuestionBtn').prop('disabled', true);
    var data = $(this).serialize();
    $.post('ajax/addQuestion.php', data, function(res){
      if (res.status === 'success') {
        $('#questionModal').modal('hide'); loadQuestions();
        Swal.fire({icon:'success', title: res.message, toast:true, position:'top-end', timer:1500, showConfirmButton:false});
      } else {
        Swal.fire('Error', res.message, 'error');
      }
    }, 'json').always(function(){ $('#saveQuestionBtn').prop('disabled', false); });
  });

  $(document).on('click', '.deleteQuestionBtn', function(){
    var id = $(this).data('id');
    Swal.fire({title:'Delete?', icon:'warning', showCancelButton:true}).then(ans=>{
      if (ans.isConfirmed) {
        $.post('ajax/deleteQuestion.php', {question_id:id}, function(res){
          if (res.status === 'success') { loadQuestions(); Swal.fire({icon:'success', title:res.message, toast:true, position:'top-end', timer:1400, showConfirmButton:false}); }
          else Swal.fire('Error', res.message,'error');
        }, 'json');
      }
    });
  });

  $(document).on('click', '.editQuestionBtn', function(){
    var id = $(this).data('id');
    $.getJSON('ajax/getQuestion.php', {question_id: id}, function(res){
      if (res.status === 'success') {
        var d = res.data;
        $('#question_id').val(d.question_id);
        $('#question_subject').val(d.subject_id);
        $('#question_text').val(d.question_text);
        $('#option_a').val(d.option_a);
        $('#option_b').val(d.option_b);
        $('#option_c').val(d.option_c);
        $('#option_d').val(d.option_d);
        $('#correct_option').val(d.correct_option);
        $('#questionModalTitle').text('Edit Question');
        $('#questionModal').modal('show');
      } else Swal.fire('Error', res.message,'error');
    });
  });

  // ---------- Exams ----------
  $('#examForm').on('submit', function(e){
    e.preventDefault();
    $('#saveExamBtn').prop('disabled', true);
    $.post('ajax/addExam.php', $(this).serialize(), function(res){
      if (res.status === 'success') {
        $('#examForm')[0].reset();
        Swal.fire({icon:'success', title: res.message, toast:true, position:'top-end', timer:1500, showConfirmButton:false});
        // optionally reload exams list (ajax not included here)
      } else Swal.fire('Error', res.message, 'error');
    }, 'json').always(function(){ $('#saveExamBtn').prop('disabled', false); });
  });
// ---------- Load Exams ----------
function loadExams() {
  $('#examsContainer').html('<div class="py-3 text-center text-muted">Loading...</div>');
  $.get('ajax/loadExams.php', function(html) {
    $('#examsContainer').html(html);
  });
}
loadExams();

// ---------- Delete Exam ----------
$(document).on('click', '.deleteExamBtn', function(){
  const id = $(this).data('id');
  Swal.fire({title:'Delete this exam?', icon:'warning', showCancelButton:true}).then(ans=>{
    if(ans.isConfirmed){
      $.post('ajax/deleteExam.php', {exam_id:id}, function(res){
        if(res.status==='success'){
          loadExams();
          Swal.fire({icon:'success', title:res.message, toast:true, position:'top-end', timer:1500, showConfirmButton:false});
        } else Swal.fire('Error', res.message, 'error');
      }, 'json');
    }
  });
});

// ---------- Edit Exam ----------
$(document).on('click', '.editExamBtn', function(){
  const id = $(this).data('id');
  $.getJSON('ajax/editExam.php', {exam_id:id}, function(res){
    if(res.status==='success'){
      const d = res.data;
      $('#exam_title').val(d.exam_title);
      $('#exam_subject').val(d.subject_id);
      $('#start_time').val(d.start_time.replace(' ', 'T'));
      $('#end_time').val(d.end_time.replace(' ', 'T'));
      $('#total_questions').val(d.total_questions);
      $('#examForm').append(`<input type="hidden" id="edit_exam_id" name="exam_id" value="${d.exam_id}">`);
      $('#saveExamBtn').text('Update Exam');
      $('html,body').animate({scrollTop:0}, 400);
    } else Swal.fire('Error', res.message, 'error');
  });
});

// ---------- Save Exam (handles both add/edit) ----------
$('#examForm').off('submit').on('submit', function(e){
  e.preventDefault();
  $('#saveExamBtn').prop('disabled', true);

  const hasId = $('#edit_exam_id').length > 0;
  const url = hasId ? 'ajax/editExam.php' : 'ajax/addExam.php';
  const data = $(this).serialize();

  $.post(url, data, function(res){
    if(res.status==='success'){
      Swal.fire({icon:'success', title:res.message, toast:true, position:'top-end', timer:1500, showConfirmButton:false});
      $('#examForm')[0].reset();
      $('#edit_exam_id').remove();
      $('#saveExamBtn').text('Save Exam');
      loadExams();
    } else Swal.fire('Error', res.message, 'error');
  }, 'json').always(()=>$('#saveExamBtn').prop('disabled', false));
});


});
