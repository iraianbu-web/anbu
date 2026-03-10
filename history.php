<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/encryption.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM secure_data WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: history.php?deleted=1');
    exit;
}

$result = $conn->query("SELECT * FROM secure_data ORDER BY created_at DESC");
$rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$deleted = isset($_GET['deleted']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AI Secure Data — History</title>
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
    <a href="history.php" class="active">History</a>
    <a href="setup.php">Setup Guide</a>
    <span style="font-family:var(--mono);font-size:11px;color:rgba(200,225,240,0.5);padding:7px 8px;">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
    <a href="logout.php" style="color:rgba(255,100,100,0.8)!important;border-color:rgba(255,32,32,0.2)!important;">⏻ Logout</a>
  </nav>
</header>

<div class="hero" style="padding-bottom:40px;">
  <div class="badge">▸ MySQL RECORDS</div>
  <h1><span>Encryption</span> History</h1>
  <p>All encrypted records stored in MySQL database.</p>
</div>

<div class="container">

  <?php if ($deleted): ?>
  <div class="alert success">✓ Record deleted successfully.</div>
  <?php endif; ?>

  <div class="card">
    <div class="card-title">// secure_data table</div>
    <h2>Stored Records <span style="font-size:0.8rem; color:var(--muted); font-weight:400;">(<?= count($rows) ?> rows)</span></h2>

    <?php if (empty($rows)): ?>
    <p style="color:var(--muted); font-family:var(--mono); font-size:13px;">No records yet. Go to <a href="index.php" style="color:var(--accent);">Encrypt</a> to add some.</p>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Original Text</th>
            <th>Encrypted Text</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int)$row['id'] ?></td>
            <td class="td-original"><?= htmlspecialchars($row['original_text']) ?></td>
            <td class="td-encrypted" title="<?= htmlspecialchars($row['encrypted_text']) ?>">
              <?= htmlspecialchars(substr($row['encrypted_text'], 0, 40)) ?>…
            </td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
              <form method="POST" onsubmit="return confirm('Delete this record?')">
                <input type="hidden" name="delete_id" value="<?= (int)$row['id'] ?>"/>
                <button type="submit" style="background:none; border:none; color:var(--red); font-family:var(--mono); font-size:12px; cursor:pointer;">✕ Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div>

<footer class="site-footer">
  AI Secure Data · <span>PHP</span> + <span>MySQL</span> + <span>AES-256-CBC</span> · Powered by XAMPP
</footer>
</body>
</html>
