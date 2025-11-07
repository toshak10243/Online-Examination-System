<?php
require_once __DIR__ . '/../dbConnection.php';
header('Content-Type: application/json');

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($fullname==='' || $username==='' || $password==='') {
  echo json_encode(['status'=>'error','message'=>'All fields required']); exit;
}

$stmt = $pdo->prepare("SELECT user_id FROM users WHERE username=?");
$stmt->execute([$username]);
if($stmt->fetch()){
  echo json_encode(['status'=>'error','message'=>'Username already exists']); exit;
}

try {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, 'student')");
  $stmt->execute([$fullname, $username, $hash]);
  echo json_encode(['status'=>'success','message'=>'Student added successfully!']);
} catch (Exception $e) {
  echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
