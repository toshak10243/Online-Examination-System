<?php
require_once 'dbConnection.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
  header('Location: login.php'); exit;
}
include 'includes/header.php';
?>

<div class="card p-4 shadow-sm mb-4">
  <h4 class="mb-3">📊 Exam Summary</h4>
  <div id="examSummary">Loading...</div>
</div>

<div class="card p-4 shadow-sm mb-4 text-center">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">📈 Student Performance Chart</h4>
    
  </div>
  <div style="max-width:600px; margin:auto;">
    <canvas id="performanceChart" height="200"></canvas>
  </div>
</div>

<div class="d-flex gap-2">
  <a href="ajax/exportCSV.php" class="btn btn-outline-primary">Download CSV</a>
  <a href="ajax/exportPDF.php" class="btn btn-outline-danger">Download PDF</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentChartType = 'pie';
let chartInstance = null;

function loadExamSummary(){
  $('#examSummary').html('<div class="text-center py-3 text-muted">Loading...</div>');
  $.getJSON('ajax/loadReportData.php', function(res){
    let html = '<table class="table table-sm table-bordered"><thead><tr><th>Exam</th><th>Subject</th><th>Appeared</th><th>Avg Score</th></tr></thead><tbody>';
    res.summary.forEach(r=>{
      html += `<tr><td>${r.exam_title}</td><td>${r.subject_name}</td><td>${r.total}</td><td>${r.avg}</td></tr>`;
    });
    html += '</tbody></table>';
    $('#examSummary').html(html);

    renderChart(res.chart.labels, res.chart.data);
  });
}

function renderChart(labels, data){
  const ctx = document.getElementById('performanceChart').getContext('2d');
  if(chartInstance) chartInstance.destroy();

  const colors = [
    '#007bff', '#28a745', '#ffc107', '#dc3545',
    '#6f42c1', '#20c997', '#fd7e14', '#6610f2'
  ];

  chartInstance = new Chart(ctx,{
    type: currentChartType,
    data:{
      labels: labels,
      datasets:[{
        label:'Average Score by Subject',
        data: data,
        backgroundColor: colors.slice(0, labels.length),
        borderColor:'#fff',
        borderWidth:2
      }]
    },
    options:{
      responsive:true,
      plugins:{
        legend:{
          position: currentChartType === 'pie' ? 'right' : 'top',
          labels:{
            boxWidth:15,
            font:{ size:13, family:'Poppins' }
          }
        },
        title:{
          display:true,
          text: currentChartType === 'pie'
                ? 'Average Score Distribution by Subject'
                : 'Average Score Comparison by Subject',
          color:'#333',
          font:{ size:15, weight:'600', family:'Poppins' },
          padding:{ top:10, bottom:20 }
        },
        tooltip:{
          callbacks:{
            label:function(context){
              const label = context.label || '';
              const value = context.parsed || 0;
              return ` ${label}: ${value}%`;
            }
          }
        }
      },
      scales: currentChartType === 'bar'
        ? { y:{ beginAtZero:true, max:100 } }
        : {}
    }
  });
}

// Toggle between Pie and Bar chart dynamically
$('#toggleChartType').on('click', function(){
  currentChartType = currentChartType === 'pie' ? 'bar' : 'pie';
  $(this).text(currentChartType === 'pie' ? 'Switch to Bar' : 'Switch to Pie');
  loadExamSummary();
});

loadExamSummary();
</script>

<?php include 'includes/footer.php'; ?>
