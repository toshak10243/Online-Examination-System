<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Online Exam System</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Exam Portal</a>

    <?php if(isset($_SESSION['user'])): ?>
      <?php $role = $_SESSION['user']['role']; ?>
      <div class="d-flex align-items-center ms-auto">
        <ul class="navbar-nav me-3">
          <?php if($role === 'admin'): ?>
            <li class="nav-item"><a href="admin_dashboard.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="admin_exams.php" class="nav-link">Manage Exams</a></li>
			<li class="nav-item"><a href="admin_students.php" class="nav-link">Manage Students</a></li>
<li class="nav-item"><a href="admin_reports.php" class="nav-link">Reports</a></li>

            <li class="nav-item"><a href="admin_results.php" class="nav-link">Results & Analysis</a></li>
          <?php elseif($role === 'student'): ?>
            <li class="nav-item"><a href="student_dashboard.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="student_results.php" class="nav-link">My Results</a></li>
			<li class="nav-item"><a href="student_leaderboard.php" class="nav-link">Leaderboard</a></li>

          <?php endif; ?>
        </ul>
        <span class="me-3 fw-semibold">Hi, <?=htmlspecialchars($_SESSION['user']['fullname'])?></span>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
      </div>
    <?php else: ?>
      <div class="ms-auto">
        <a class="btn btn-outline-primary btn-sm" href="login.php">Login</a>
      </div>
    <?php endif; ?>
  </div>
</nav>
<div class="container">

