<?php
require_once 'includes/config.php';
session_start();

// Already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = 'Please enter both username and password.';
        $msg_type = 'error';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $msg = '✗ Invalid username or password.';
            $msg_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Secure Data — Login</title>
  <link rel="stylesheet" href="assets/css/style.css"/>
  <style>
    .login-wrap {
      min-height: calc(100vh - 160px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }
    .login-card {
      width: 100%;
      max-width: 420px;
      background: linear-gradient(135deg,
        rgba(255,255,255,0.78) 0%,
        rgba(220,240,255,0.6)  50%,
        rgba(255,200,200,0.2)  100%
      );
      backdrop-filter: blur(24px);
      border: 1px solid rgba(255,32,32,0.22);
      border-radius: 22px;
      padding: 44px 40px;
      box-shadow:
        0 20px 60px rgba(139,0,0,0.18),
        0 4px 12px rgba(135,206,235,0.2),
        inset 0 1px 0 rgba(255,255,255,0.7);
      position: relative;
      overflow: hidden;
    }
    .login-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, #ff2020, #ff6666, #ff2020, transparent);
      opacity: 0.8;
    }
    .login-icon {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, #cc0000, #ff4444);
      border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      font-size: 28px;
      margin: 0 auto 24px;
      box-shadow: 0 8px 24px rgba(255,32,32,0.35);
    }
    .login-card h2 {
      font-family: var(--mono);
      font-size: 1.4rem;
      font-weight: 900;
      color: #1a0505;
      text-align: center;
      margin-bottom: 6px;
    }
    .login-card .subtitle {
      font-family: var(--mono);
      font-size: 10px;
      letter-spacing: 2px;
      color: var(--red-bright);
      text-align: center;
      margin-bottom: 32px;
      text-transform: uppercase;
    }
    .input-group {
      position: relative;
      margin-bottom: 18px;
    }
    .input-group label {
      margin-bottom: 6px;
    }
    .input-group input {
      width: 100%;
      background: rgba(255,255,255,0.7);
      border: 1px solid rgba(255,32,32,0.25);
      border-radius: 10px;
      padding: 13px 16px 13px 44px;
      color: #1a0505;
      font-family: var(--body);
      font-size: 0.95rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-group input:focus {
      outline: none;
      border-color: var(--red-bright);
      box-shadow: 0 0 0 3px rgba(255,32,32,0.12);
    }
    .input-group input::placeholder { color: rgba(90,42,42,0.45); }
    .input-icon {
      position: absolute;
      bottom: 13px; left: 14px;
      font-size: 17px;
      pointer-events: none;
    }
    .btn-login {
      width: 100%;
      justify-content: center;
      font-size: 13px;
      padding: 14px;
      margin-top: 8px;
      letter-spacing: 3px;
    }
    .divider {
      text-align: center;
      font-family: var(--mono);
      font-size: 10px;
      color: rgba(90,42,42,0.5);
      margin: 20px 0 0;
      letter-spacing: 1px;
    }
    .default-creds {
      margin-top: 16px;
      background: rgba(255,204,0,0.1);
      border: 1px solid rgba(255,204,0,0.3);
      border-radius: 10px;
      padding: 12px 16px;
      font-family: var(--mono);
      font-size: 11px;
      color: #664d00;
      line-height: 1.7;
    }
    .default-creds strong { color: #8b0000; }
  </style>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="cloud cloud-1"></div>
<div class="cloud cloud-2"></div>
<div class="cloud cloud-3"></div>
<div class="cloud cloud-4"></div>

<header class="site-header">
  <a class="logo" href="login.php">AI<span>Secure</span>.php</a>
  <nav>
    <a href="login.php" class="active">Login</a>
  </nav>
</header>

<div class="login-wrap">
  <div class="login-card">
    <div class="login-icon">🔐</div>
    <h2>Welcome Back</h2>
    <div class="subtitle">// secure access required</div>

    <?php if ($msg): ?>
    <div class="alert <?= $msg_type ?>" style="margin-bottom:20px;"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="input-group">
        <label for="username">Username</label>
        <span class="input-icon">👤</span>
        <input
          type="text"
          id="username"
          name="username"
          placeholder="Enter username"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
          autocomplete="username"
        />
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <span class="input-icon">🔑</span>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter password"
          autocomplete="current-password"
        />
      </div>

      <button type="submit" class="btn btn-login">🔓 Sign In</button>
    </form>

    <div class="divider">── default credentials ──</div>
    <div class="default-creds">
      Username: <strong>admin</strong><br>
      Password: <strong>admin123</strong><br>
      <span style="opacity:0.7;">⚠ Change these after first login!</span>
    </div>
  </div>
</div>

<footer class="site-footer">
  AI Secure Data · <span>PHP</span> + <span>MySQL</span> + <span>AES-256-CBC</span> · Powered by XAMPP
</footer>
</body>
</html>
