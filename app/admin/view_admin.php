<?php
include('../config/database.php');

$admin_id = intval($_GET['id']);

$query = $conn->prepare("
  SELECT aktivitas, jenis, created_at
  FROM log_aktivitas
  WHERE admin_id = ?
  ORDER BY created_at DESC
  LIMIT 20
");
$query->bind_param("i", $admin_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
  echo "<p>Tidak ada aktivitas.</p>";
  exit;
}

while ($row = $result->fetch_assoc()):
  $status = match($row['jenis']) {
    'login' => '🟢 Login',
    'update' => '🟡 Update',
    'delete' => '🔴 Delete',
    'password' => '🔐 Password',
    default => 'ℹ️ Lainnya'
  };
?>
  <div class="activity-item">
    <strong><?= $status; ?></strong><br>
    <?= htmlspecialchars($row['aktivitas']); ?><br>
    <small><?= date('d M Y H:i', strtotime($row['created_at'])); ?></small>
  </div>
<?php endwhile; ?>
