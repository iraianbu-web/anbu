<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/encryption.php';

$encrypted = '';
$decrypted = '';
$original  = '';
$msg       = '';
$msg_type  = '';

$key_configured = (ENCRYPT_KEY !== 'YOUR_BASE64_KEY_HERE_32_BYTES');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $key_configured) {
    $action = $_POST['action'] ?? '';

    if ($action === 'encrypt') {
        $original = trim($_POST['text'] ?? '');
        if ($original === '') {
            $msg = 'Please enter some text to encrypt.';
            $msg_type = 'error';
        } else {
            try {
                $encrypted = encrypt_text($original);
                $decrypted = decrypt_text($encrypted);
                $stmt = $conn->prepare("INSERT INTO secure_data (original_text, encrypted_text) VALUES (?, ?)");
                $stmt->bind_param('ss', $original, $encrypted);
                $stmt->execute();
                $stmt->close();
                $msg = '✓ Text encrypted and saved successfully.';
                $msg_type = 'success';
            } catch (Exception $e) {
                $msg = 'Encryption error: ' . htmlspecialchars($e->getMessage());
                $msg_type = 'error';
            }
        }
    }

    if ($action === 'decrypt') {
        $enc_input = trim($_POST['encrypted_input'] ?? '');
        if ($enc_input === '') {
            $msg = 'Please paste an encrypted string to decrypt.';
            $msg_type = 'error';
        } else {
            try {
                $decrypted = decrypt_text($enc_input);
                $encrypted = $enc_input;
                $msg = '✓ Decryption successful.';
                $msg_type = 'success';
            } catch (Exception $e) {
                $msg = '✗ Decryption failed — invalid key or corrupted data.';
                $msg_type = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Secure Data — Encrypt &amp; Decrypt</title>
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
    <a href="index.php" class="active">Encrypt / Decrypt</a>
    <a href="history.php">History</a>
    <a href="setup.php">Setup Guide</a>
    <span style="font-family:var(--mono);font-size:11px;color:rgba(200,225,240,0.5);padding:7px 8px;">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
    <a href="logout.php" style="color:rgba(255,100,100,0.8)!important;border-color:rgba(255,32,32,0.2)!important;">⏻ Logout</a>
  </nav>
</header>

<div class="hero">
  <div class="badge">▸ XAMPP · PHP · MySQL · AES-256</div>
  <h1>AI <span>Secure Data</span><br>Project</h1>
  <p>Encrypt and decrypt text securely using AES-256-CBC encryption, stored in MySQL via XAMPP.</p>
</div>

<div class="container">

  <?php if (!$key_configured): ?>
  <div class="alert warning">
    ⚠ Encryption key not configured. Visit <a href="generate_key.php" style="color:var(--yellow)">generate_key.php</a> to create your key, paste it into <code>includes/config.php</code>, then delete that file.
  </div>
  <?php endif; ?>

  <?php if ($msg): ?>
  <div class="alert <?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-title">// encrypt</div>
    <h2>Encrypt Text</h2>
    <form method="POST" action="index.php">
      <input type="hidden" name="action" value="encrypt"/>
      <label for="text">Plain Text</label>
      <textarea id="text" name="text" rows="4" placeholder="Enter text to encrypt..."><?= htmlspecialchars($original) ?></textarea>
      <button type="submit" class="btn" <?= !$key_configured ? 'disabled' : '' ?>>🔒 Encrypt &amp; Save</button>
    </form>
    <?php if ($encrypted && ($_POST['action'] ?? '') === 'encrypt'): ?>
    <div class="result-box">
      <div class="result-item">
        <label>Encrypted Output (AES-256-CBC)</label>
        <div class="code-block"><?= htmlspecialchars($encrypted) ?></div>
      </div>
      <div class="result-item">
        <label>Decrypted Verification</label>
        <div class="code-block" style="color:var(--accent);"><?= htmlspecialchars($decrypted) ?></div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <div class="card">
    <div class="card-title">// decrypt</div>
    <h2>Decrypt Text</h2>
    <form method="POST" action="index.php">
      <input type="hidden" name="action" value="decrypt"/>
      <label for="encrypted_input">Encrypted String</label>
      <textarea id="encrypted_input" name="encrypted_input" rows="4" placeholder="Paste encrypted string here..."><?= htmlspecialchars(($_POST['action'] ?? '') === 'decrypt' ? ($_POST['encrypted_input'] ?? '') : '') ?></textarea>
      <button type="submit" class="btn btn-secondary" <?= !$key_configured ? 'disabled' : '' ?>>🔓 Decrypt</button>
    </form>
    <?php if ($decrypted && ($_POST['action'] ?? '') === 'decrypt'): ?>
    <div class="result-box">
      <div class="result-item">
        <label>Decrypted Result</label>
        <div class="code-block" style="color:var(--accent);"><?= htmlspecialchars($decrypted) ?></div>
      </div>
    </div>
    <?php endif; ?>
  </div>

</div>

<footer class="site-footer">
  AI Secure Data · <span>PHP</span> + <span>MySQL</span> + <span>AES-256-CBC</span> · Powered by XAMPP
</footer>
</body>
</html>
