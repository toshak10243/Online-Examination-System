<?php
require_once 'dbConnection.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Enter username and password';
    } else {
        // fetch user by username
        $stmt = $pdo->prepare("SELECT user_id, username, fullname, role, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $u = $stmt->fetch();

        if ($u) {
            $stored = $u['password'];
            $login_ok = false;

            // 1️⃣ New secure hash users (password_hash)
            if ((substr($stored, 0, 4) === '$2y$') || (substr($stored,0,6) === '$argon')) {
                if (password_verify($password, $stored)) {
                    $login_ok = true;
                }
            }

            // 2️⃣ Old SHA-256 legacy users (admin/student demo)
            if (!$login_ok && hash('sha256', $password) === $stored) {
                $login_ok = true;
                // Optional migration: upgrade SHA2 → password_hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $upd->execute([$newHash, $u['user_id']]);
            }

            if ($login_ok) {
                // login success
                $_SESSION['user'] = [
                    'user_id' => $u['user_id'],
                    'username' => $u['username'],
                    'fullname' => $u['fullname'],
                    'role' => $u['role']
                ];
                if ($u['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: student_dashboard.php');
                }
                exit;
            } else {
                $error = 'Invalid credentials';
            }
        } else {
            $error = 'Invalid credentials';
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card p-4 shadow-sm">
      <h4 class="mb-3">Login</h4>
      <?php if($error): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-2">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required />
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">Login</button>
        </div>
      </form>
      <div class="mt-3 text-muted small">
        Admin: <b>admin / admin123</b><br>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
