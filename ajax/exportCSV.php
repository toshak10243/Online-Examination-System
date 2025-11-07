<?php
// ajax/exportCSV.php
require_once __DIR__ . '/../dbConnection.php';

// prevent accidental output (no includes, no HTML)
if (headers_sent()) {
    // if headers already sent, abort to avoid corrupt CSV
    exit('Headers already sent. Cannot send CSV.');
}

// CSV filename
$filename = 'exam_report_' . date('Ymd_His') . '.csv';

// Send headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'. $filename .'"');
header('Pragma: no-cache');
header('Expires: 0');

// open output stream
$out = fopen('php://output', 'w');

// Optional: add UTF-8 BOM so Excel recognizes UTF-8
// Comment out if you don't want BOM
fwrite($out, "\xEF\xBB\xBF");

// Write header row
// fputcsv with explicit separator, enclosure, escape (PHP 8.3+)
fputcsv($out, ['Exam', 'Subject', 'Students Appeared', 'Average Score'], ',', '"', "\\");

// Fetch data
$sql = "SELECT e.exam_title, s.subject_name,
        COUNT(ea.attempt_id) AS total,
        ROUND(AVG(ea.score),1) AS avg
        FROM exam_attempts ea
        JOIN exams e ON ea.exam_id=e.exam_id
        JOIN subjects s ON e.subject_id=s.subject_id
        WHERE ea.submitted=1
        GROUP BY e.exam_id
        ORDER BY e.created_at DESC";
$stmt = $pdo->query($sql);

// Write rows
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // ensure values are scalar/strings
    $row = [
        $r['exam_title'] ?? '',
        $r['subject_name'] ?? '',
        $r['total'] ?? 0,
        $r['avg'] ?? 0
    ];
    fputcsv($out, $row, ',', '"', "\\");
}

// close stream and exit
fclose($out);
exit;
