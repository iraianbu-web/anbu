<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Secure Data — Setup Guide</title>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="cloud cloud-1"></div>
<div class="cloud cloud-2"></div>
<div class="cloud cloud-3"></div>
<div class="cloud cloud-4"></div>

<header class="site-header">
  <a class="logo" href="index.php">AI<span>Secure</span>.php</a>
  <nav>
    <a href="index.php">Encrypt / Decrypt</a>
    <a href="history.php">History</a>
    <a href="setup.php" class="active">Setup Guide</a>
    <span style="font-family:var(--mono);font-size:11px;color:rgba(200,225,240,0.5);padding:7px 8px;">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
    <a href="logout.php" style="color:rgba(255,100,100,0.8)!important;border-color:rgba(255,32,32,0.2)!important;">⏻ Logout</a>
  </nav>
</header>

<div class="hero" style="padding-bottom:40px;">
  <div class="badge">▸ XAMPP SETUP GUIDE</div>
  <h1><span>Installation</span><br>Guide</h1>
  <p>Get the AI Secure Data project running on XAMPP in minutes.</p>
</div>

<div class="container">

  <div class="card" style="margin-bottom:40px;">
    <div class="card-title">// system status</div>
    <h2>Live Diagnostics</h2>
    <?php
      $php_ok = version_compare(PHP_VERSION, '7.4', '>=');
      echo '<div class="status-row"><div class="dot ' . ($php_ok ? 'ok' : 'fail') . '"></div>PHP ' . PHP_VERSION . ($php_ok ? ' — OK' : ' — Requires 7.4+') . '</div>';

      $ssl_ok = extension_loaded('openssl');
      echo '<div class="status-row"><div class="dot ' . ($ssl_ok ? 'ok' : 'fail') . '"></div>OpenSSL extension — ' . ($ssl_ok ? 'Loaded ✓' : 'NOT loaded ✗') . '</div>';

      $mysqli_ok = extension_loaded('mysqli');
      echo '<div class="status-row"><div class="dot ' . ($mysqli_ok ? 'ok' : 'fail') . '"></div>MySQLi extension — ' . ($mysqli_ok ? 'Loaded ✓' : 'NOT loaded ✗') . '</div>';

      $db_ok = !$conn->connect_error;
      echo '<div class="status-row"><div class="dot ' . ($db_ok ? 'ok' : 'fail') . '"></div>MySQL connection — ' . ($db_ok ? 'Connected ✓' : 'Failed: ' . htmlspecialchars($conn->connect_error)) . '</div>';

      $tbl = $conn->query("SHOW TABLES LIKE 'secure_data'");
      $tbl_ok = $tbl && $tbl->num_rows > 0;
      echo '<div class="status-row"><div class="dot ' . ($tbl_ok ? 'ok' : 'warn') . '"></div>Table <code>secure_data</code> — ' . ($tbl_ok ? 'Exists ✓' : 'Not found — run database.sql') . '</div>';

      $key_ok = ENCRYPT_KEY !== 'YOUR_BASE64_KEY_HERE_32_BYTES';
      echo '<div class="status-row"><div class="dot ' . ($key_ok ? 'ok' : 'warn') . '"></div>ENCRYPT_KEY — ' . ($key_ok ? 'Configured ✓' : 'Not set — see Step 4') . '</div>';
    ?>
  </div>

  <div class="card">
    <div class="card-title">// installation steps</div>
    <h2>Step-by-Step</h2>

    <div class="step">
      <div class="step-num"><span>01</span></div>
      <div>
        <h3>Download &amp; Start XAMPP</h3>
        <p>Download XAMPP from <a href="https://www.apachefriends.org" target="_blank" style="color:var(--accent)">apachefriends.org</a>, install it, then start <strong>Apache</strong> and <strong>MySQL</strong> from the Control Panel.</p>
      </div>
    </div>

    <div class="step">
      <div class="step-num"><span>02</span></div>
      <div>
        <h3>Copy Project to htdocs</h3>
        <p>Place the <code>ai_secure_data</code> folder inside XAMPP's web root:</p>
        <pre><span class="cm"># Windows</span>
C:\xampp\htdocs\ai_secure_data\

<span class="cm"># macOS</span>
/Applications/XAMPP/htdocs/ai_secure_data/

<span class="cm"># Linux</span>
/opt/lampp/htdocs/ai_secure_data/</pre>
      </div>
    </div>

    <div class="step">
      <div class="step-num"><span>03</span></div>
      <div>
        <h3>Create the Database</h3>
        <p>Open <a href="http://localhost/phpmyadmin" target="_blank" style="color:var(--accent)">phpMyAdmin</a>, click <strong>SQL</strong>, and run <code>database.sql</code>.</p>
      </div>
    </div>

    <div class="step">
      <div class="step-num"><span>04</span></div>
      <div>
        <h3>Generate &amp; Set Encryption Key</h3>
        <p>Visit <a href="generate_key.php" style="color:var(--accent)">generate_key.php</a> to get a key. Copy it, open <code>includes/config.php</code>, and paste it:</p>
        <pre><span class="cm">// includes/config.php</span>
define(<span class="str">'ENCRYPT_KEY'</span>, <span class="str">'paste-your-key-here'</span>);</pre>
        <p style="margin-top:10px;">Then <strong>delete</strong> <code>generate_key.php</code> from the server.</p>
      </div>
    </div>

    <div class="step">
      <div class="step-num"><span>05</span></div>
      <div>
        <h3>Configure DB Password (if needed)</h3>
        <p>If your MySQL root user has a password, update <code>includes/config.php</code>:</p>
        <pre>define(<span class="str">'DB_PASS'</span>, <span class="str">'your_mysql_password'</span>);</pre>
      </div>
    </div>

    <div class="step">
      <div class="step-num"><span>06</span></div>
      <div>
        <h3>Open the App</h3>
        <p>Navigate to:</p>
        <pre>http://localhost/ai_secure_data/</pre>
      </div>
    </div>

  </div>
</div>

<footer class="site-footer">
  AI Secure Data · <span>PHP</span> + <span>MySQL</span> + <span>AES-256-CBC</span> · Powered by XAMPP
</footer>
</body>
</html>
