<?php
$key = base64_encode(random_bytes(32));
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Key Generator</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="cloud cloud-1"></div>
<div class="cloud cloud-2"></div>
<div class="cloud cloud-3"></div>

<header class="site-header">
  <a class="logo" href="index.php">AI<span>Secure</span>.php</a>
  <nav>
    <a href="index.php">Encrypt / Decrypt</a>
    <a href="history.php">History</a>
    <a href="setup.php">Setup Guide</a>
  </nav>
</header>

<div class="container" style="padding-top:80px; max-width:700px;">
  <div class="card">
    <div class="card-title">// one-time key generator</div>
    <h2>🔑 Generate Encryption Key</h2>
    <p style="color:var(--muted); margin-bottom:20px;">Copy this key into <code>includes/config.php</code> as <code>ENCRYPT_KEY</code>, then <strong>delete this file</strong>.</p>
    <div class="code-block" style="word-break:break-all; user-select:all;"><?= htmlspecialchars($key) ?></div>
    <div class="alert warning" style="margin-top:20px;">⚠ Never change this key after encrypting data — you will lose access to all stored records.</div>
  </div>
</div>

<footer class="site-footer">
  AI Secure Data · <span>PHP</span> + <span>MySQL</span> + <span>AES-256-CBC</span> · Powered by XAMPP
</footer>
</body>
</html>
