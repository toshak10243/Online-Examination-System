<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='student'){ header('Location: login.php'); exit; }
$exam_id = (int)($_POST['exam_id'] ?? 0);
$student_id = $_SESSION['user']['user_id'];

/* prevent re-attempt */
$chk = $pdo->prepare("SELECT attempt_id, submitted FROM exam_attempts WHERE exam_id=? AND student_id=?");
$chk->execute([$exam_id, $student_id]);
if($a=$chk->fetch()){ 
  if($a['submitted']) die("<script>alert('Already submitted!');location='student_dashboard.php';</script>");
  $attempt_id = $a['attempt_id'];
} else {
  $pdo->prepare("INSERT INTO exam_attempts (exam_id, student_id) VALUES (?,?)")->execute([$exam_id,$student_id]);
  $attempt_id = $pdo->lastInsertId();
}

/* fetch exam details first */
$examStmt = $pdo->prepare("SELECT e.subject_id, e.total_questions, e.start_time, e.end_time 
                           FROM exams e WHERE e.exam_id=?");
$examStmt->execute([$exam_id]);
$exam = $examStmt->fetch();
if(!$exam){ die('Exam not found.'); }

$totalQuestions = (int)$exam['total_questions'];
$subject_id = (int)$exam['subject_id'];

/* fetch that many random questions for the subject */
$stmt = $pdo->prepare("SELECT q.* FROM questions q WHERE q.subject_id=? ORDER BY RAND() LIMIT $totalQuestions");
$stmt->execute([$subject_id]);
$questions = $stmt->fetchAll();
if(!$questions){ die('No questions found for this subject.'); }


/* prepare answers placeholders */
foreach($questions as $q){
  $pdo->prepare("INSERT IGNORE INTO exam_answers (attempt_id, question_id) VALUES (?,?)")->execute([$attempt_id, $q['question_id']]);
}

/* exam time left (in seconds) */
$exam = $pdo->query("SELECT start_time,end_time FROM exams WHERE exam_id=$exam_id")->fetch();
$end_ts = strtotime($exam['end_time']);
$timeLeft = max(0, $end_ts - time());

include 'includes/header.php';
?>
<div class="card p-3">
  <div class="d-flex justify-content-between align-items-center">
    <h5>Exam #<?=$exam_id?></h5>
    <div><span id="timer" class="fw-bold text-danger"></span></div>
  </div>
  <hr>
  <div id="examContent">
    <input type="hidden" id="attempt_id" value="<?=$attempt_id?>">
    <input type="hidden" id="exam_id" value="<?=$exam_id?>">
    <div id="questionArea"></div>
    <div class="mt-3 d-flex justify-content-between">
      <button class="btn btn-outline-secondary" id="prevBtn">Previous</button>
      <button class="btn btn-outline-secondary" id="nextBtn">Next</button>
      <button class="btn btn-success d-none" id="submitExamBtn">Submit Exam</button>
    </div>
  </div>
</div>
<script>
const questions = <?=json_encode($questions)?>;
let index=0;
let timeLeft=<?=$timeLeft?>;

function loadQuestion(){
  const q=questions[index];
  $('#questionArea').html(`
    <h6>Q${index+1}. ${q.question_text}</h6>
    ${['A','B','C','D'].map(opt=>{
      const txt=q['option_'+opt.toLowerCase()];
      return `<div><label><input type="radio" name="option" value="${opt}" class="me-2">${opt}. ${txt}</label></div>`;
    }).join('')}
  `);
  $('#prevBtn').prop('disabled', index===0);
  if(index===questions.length-1){
    $('#nextBtn').addClass('d-none');
    $('#submitExamBtn').removeClass('d-none');
  } else {
    $('#nextBtn').removeClass('d-none');
    $('#submitExamBtn').addClass('d-none');
  }
  loadSavedAnswer(q.question_id);
}

function loadSavedAnswer(qid){
  $.getJSON('ajax/startExam.php',{action:'get',attempt_id:$('#attempt_id').val(),qid},res=>{
    if(res.option){
      $(`input[name=option][value=${res.option}]`).prop('checked',true);
    }
  });
}

function saveAnswer(qid,opt){
  $.post('ajax/saveAnswer.php',{attempt_id:$('#attempt_id').val(),qid,option:opt});
}

$(document).on('change','input[name=option]',function(){
  const opt=$(this).val();
  const qid=questions[index].question_id;
  saveAnswer(qid,opt);
});

$('#nextBtn').click(()=>{ if(index<questions.length-1){ index++; loadQuestion(); } });
$('#prevBtn').click(()=>{ if(index>0){ index--; loadQuestion(); } });
$('#submitExamBtn').click(()=>{
  Swal.fire({title:'Submit exam?',showCancelButton:true}).then(a=>{
    if(a.isConfirmed){
      $.post('ajax/submitExam.php',{attempt_id:$('#attempt_id').val()},res=>{
        Swal.fire('Submitted',res.message,'success').then(()=>location='student_dashboard.php');
      },'json');
    }
  });
});

/* timer */
function timerTick(){
  const m=Math.floor(timeLeft/60), s=timeLeft%60;
  $('#timer').text(`${m}:${s.toString().padStart(2,'0')}`);
  if(timeLeft<=0){
    $.post('ajax/submitExam.php',{attempt_id:$('#attempt_id').val()},()=>location='student_dashboard.php');
  } else { timeLeft--; setTimeout(timerTick,1000); }
}
timerTick();

loadQuestion();
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/*
  🔒 STRICT ANTI-CHEAT MODE (Final Version)
  - If student exits Full Screen OR switches browser tab → instant termination
  - Shows message “You tried to cheat”
  - Auto-submits exam with 0 marks
  - Logs out and redirects to login.php
*/

(function(){
  // Disable right-click, copy, paste, text select, etc.
  ['contextmenu','copy','paste','cut','selectstart','dragstart'].forEach(evt =>
    document.addEventListener(evt, e => e.preventDefault(), {passive:false})
  );

  let examTerminated = false;
  const attemptId = () => $('#attempt_id').val();

  // Enter full screen on start
  function enterFullScreen() {
    const el = document.documentElement;
    if (el.requestFullscreen) el.requestFullscreen().catch(()=>{});
    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
    else if (el.msRequestFullscreen) el.msRequestFullscreen();
  }

  // 🚨 Terminate Exam Function
  function terminateExam(reason) {
    if (examTerminated) return;
    examTerminated = true;

    Swal.fire({
      icon: 'error',
      title: 'You Tried to Cheat!',
      html: `<div style="text-align:left">
              <b>Reason:</b> ${reason}<br>
              Your exam has been terminated and submitted with 0 marks.
            </div>`,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: 'OK',
      confirmButtonColor: '#d33'
    }).then(() => {
      const attempt = attemptId();

      // Submit with 0 marks
      $.post('ajax/submitExam.php', { attempt_id: attempt, forced: 1, score: 0 }, function(){
        // Then logout and redirect
        $.get('logout.php').always(() => {
          window.location.href = 'login.php';
        });
      }, 'json').fail(() => {
        $.get('logout.php').always(() => {
          window.location.href = 'login.php';
        });
      });
    });
  }

  // 🎯 Detect TAB SWITCH / window unfocus
  document.addEventListener('visibilitychange', () => {
    if (examTerminated) return;
    if (document.hidden) {
      terminateExam('You switched to another tab or minimized the window.');
    }
  });

  // 🎯 Detect EXIT from Full Screen
  function onFullScreenChange() {
    if (examTerminated) return;
    const fs = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
    if (!fs) {
      terminateExam('You exited full screen mode.');
    }
  }
  document.addEventListener('fullscreenchange', onFullScreenChange);
  document.addEventListener('webkitfullscreenchange', onFullScreenChange);
  document.addEventListener('msfullscreenchange', onFullScreenChange);

  // ⚙️ Detect keyboard-based tab switching
  window.addEventListener('keydown', function(e) {
    // Ctrl+Tab, Alt+Tab, Ctrl+T, Ctrl+N, Ctrl+W, F11
    if ((e.ctrlKey && (e.key === 'Tab' || e.key === 't' || e.key === 'n' || e.key === 'w' || e.key === 'T' || e.key === 'N' || e.key === 'W')) 
        || e.key === 'F11' || e.altKey) {
      e.preventDefault();
      terminateExam('You tried switching tabs or using a shortcut.');
    }
  }, {passive:false});

  // 💡 Start Exam Prompt
  $(document).ready(() => {
    // Add a small top banner
    $('body').prepend(`
      <div class="alert alert-warning text-center py-2 mb-0" style="font-size:0.95rem;">
        ⚠️ Exam in Full Screen. Any tab switch or fullscreen exit will terminate the exam immediately.
      </div>
    `);

    // Ask before entering full screen
    Swal.fire({
      icon: 'info',
      title: 'Full Screen Required',
      html: 'Click <b>Start Exam</b> to begin. Leaving full screen or switching tabs will terminate your exam immediately.',
      confirmButtonText: 'Start Exam',
      allowOutsideClick: false
    }).then(() => {
      enterFullScreen();
    });
  });
})();
</script>


<?php include 'includes/footer.php'; ?>
