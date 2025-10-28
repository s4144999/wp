<?php
// /wp/a3/delete.php
session_start();
require __DIR__ . '/includes/db_connect.inc';

if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}

if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  http_response_code(400);
  exit('Bad request (CSRF).');
}

if (empty($_POST['id']) || !ctype_digit($_POST['id'])) {
  http_response_code(400);
  exit('Missing id.');
}
$skill_id = (int)$_POST['id'];

/* Fetch record to 1) check owner 2) know image to unlink */
$sql = "SELECT user_id, image_path FROM skills WHERE skill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $skill_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
  http_response_code(404);
  exit('Skill not found.');
}
$row = $res->fetch_assoc();
$stmt->close();

/* Owner check */
if (empty($_SESSION['user_id']) || (int)$_SESSION['user_id'] !== (int)$row['user_id']) {
  http_response_code(403);
  exit('Forbidden.');
}

/* Delete row (AND ensure owner in WHERE for extra safety) */
$del = $conn->prepare("DELETE FROM skills WHERE skill_id = ? AND user_id = ?");
$del->bind_param('ii', $skill_id, $_SESSION['user_id']);
$ok = $del->execute();
$del->close();

if ($ok) {
  // remove image file (if any)
  $UPLOAD_FS = __DIR__ . '/assets/images/skills/';
  if (!empty($row['image_path'])) {
    $abs = $UPLOAD_FS . basename($row['image_path']);
    if (is_file($abs)) @unlink($abs);
  }

  $_SESSION['flash'] = 'Skill deleted.';
  header('Location: index.php');
  exit;
}

http_response_code(500);
exit('Delete failed: ' . htmlspecialchars($conn->error));
