<?php
/* /wp/a3/edit.php : Edit a skill (owner-only), optionally replace image */
session_start();
require __DIR__ . '/includes/db_connect.inc';

/* ---------- 0) CSRF token ---------- */
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

/* ---------- 1) Validate id ---------- */
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
  http_response_code(400);
  exit('Invalid request: missing id.');
}
$skill_id = (int)$_GET['id'];

/* ---------- 2) Fetch the skill (and owner) ---------- */
$sql = "SELECT skill_id, user_id, title, description, category, image_path, rate_per_hr, level, created_at
        FROM skills WHERE skill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $skill_id);
$stmt->execute();
$res  = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
  http_response_code(404);
  exit('Skill not found.');
}
$skill = $res->fetch_assoc();
$stmt->close();

/* ---------- 3) Ownership check ---------- */
if (empty($_SESSION['user_id']) || (int)$_SESSION['user_id'] !== (int)$skill['user_id']) {
  http_response_code(403);
  exit('Forbidden: you do not own this record.');
}

/* ---------- 4) Upload paths ---------- */
$UPLOAD_FS = __DIR__ . '/assets/images/skills/';   // filesystem
if (!is_dir($UPLOAD_FS)) { @mkdir($UPLOAD_FS, 0755, true); }

/* ---------- 5) Helpers ---------- */
function safe_upload_name(string $original): string {
  $ext  = strtolower(pathinfo($original, PATHINFO_EXTENSION));
  $base = preg_replace('~[^a-z0-9_-]+~i', '-', pathinfo($original, PATHINFO_FILENAME));
  $base = trim($base, '-');
  if ($base === '') $base = 'upload';
  return uniqid('skill_', true) . '-' . $base . ($ext ? ".$ext" : '');
}

/* ---------- 6) Handle POST ---------- */
$errors  = [];
$updated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /* CSRF */
  if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
    http_response_code(400);
    exit('Bad request (CSRF).');
  }

  $title       = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $category    = trim($_POST['category'] ?? '');
  $level       = $_POST['level'] ?? '';
  $rate        = $_POST['rate'] ?? '';
  $newImageRel = null; // set if new image uploaded

  // validation
  if ($title === '')        $errors[] = 'Title is required.';
  if ($description === '')  $errors[] = 'Description is required.';
  if ($category === '')     $errors[] = 'Category is required.';
  if (!in_array($level, ['Beginner','Intermediate','Expert'], true)) {
    $errors[] = 'Invalid level.';
  }
  if ($rate === '' || !is_numeric($rate) || (float)$rate < 0) {
    $errors[] = 'Rate must be a positive number.';
  } else {
    $rate = (float)$rate;
  }

  // optional image
  if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Image upload failed.';
    } else {
      $allowedExt  = ['jpg','jpeg','png','gif','webp'];
      $allowedMime = ['image/jpeg','image/png','image/gif','image/webp'];

      $name = $_FILES['image']['name'];
      $tmp  = $_FILES['image']['tmp_name'];
      $size = (int)$_FILES['image']['size'];
      $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

      if (!in_array($ext, $allowedExt, true)) {
        $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedExt) . '.';
      }
      if ($size > 5*1024*1024) {
        $errors[] = 'Image too large (max 5MB).';
      }

      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime  = $finfo ? finfo_file($finfo, $tmp) : '';
      if ($finfo) finfo_close($finfo);
      if ($mime && !in_array($mime, $allowedMime, true)) {
        $errors[] = 'Invalid image content.';
      }

      if (!$errors) {
        $newName = safe_upload_name($name);
        $destAbs = $UPLOAD_FS . $newName;
        if (!move_uploaded_file($tmp, $destAbs)) {
          $errors[] = 'Server error: failed saving the image.';
        } else {
          // store relative (file name only – your app already serves from /assets/images/skills/)
          $newImageRel = $newName;
        }
      }
    }
  }

  // update DB
  if (!$errors) {
    if ($newImageRel !== null) {
      $sql = "UPDATE skills
              SET title=?, description=?, category=?, rate_per_hr=?, level=?, image_path=?
              WHERE skill_id=? AND user_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('sssdssii',
        $title, $description, $category, $rate, $level, $newImageRel, $skill_id, $_SESSION['user_id']
      );
    } else {
      $sql = "UPDATE skills
              SET title=?, description=?, category=?, rate_per_hr=?, level=?
              WHERE skill_id=? AND user_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('sssd sii',
        $title, $description, $category, $rate, $level, $skill_id, $_SESSION['user_id']
      );
      // Note: space in 'sssd sii' is harmless; you can also write 'sssdsii'
      $stmt->bind_param('sssdsii',
        $title, $description, $category, $rate, $level, $skill_id, $_SESSION['user_id']
      );
    }

    if (!$stmt) {
      $errors[] = 'DB error (prepare): ' . $conn->error;
    } else {
      if ($stmt->execute() && $stmt->affected_rows >= 0) {
        $updated = true;

        // If new image saved -> remove the old one (if it exists and is not same)
        if ($newImageRel !== null) {
          $old = $skill['image_path'];
          if ($old && $old !== $newImageRel) {
            $oldAbs = $UPLOAD_FS . basename($old);
            if (is_file($oldAbs)) @unlink($oldAbs);
          }
          // update current skill array for form re-render if needed
          $skill['image_path'] = $newImageRel;
        }

        // Update current skill fields
        $skill['title']       = $title;
        $skill['description'] = $description;
        $skill['category']    = $category;
        $skill['rate_per_hr'] = $rate;
        $skill['level']       = $level;

        $_SESSION['flash'] = 'Skill updated successfully.';
        header('Location: details.php?id=' . $skill_id);
        exit;
      } else {
        $errors[] = 'DB error (execute): ' . $stmt->error;
      }
      $stmt->close();
    }
  }
}

/* ---------- 7) View (prefilled) ---------- */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'includes/header.inc'; ?>
  </head>
  <body>
    <?php include 'includes/nav.inc'; ?>

    <main class="container py-4" style="max-width:900px;">
      <h2 class="mb-4">Edit Skill</h2>

      <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
      <?php endforeach; ?>

      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control"
                 value="<?= htmlspecialchars($skill['title']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" rows="6" class="form-control" required><?= htmlspecialchars($skill['description']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-control"
                 value="<?= htmlspecialchars($skill['category']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Rate per Hour ($)</label>
          <input type="number" step="0.01" name="rate" class="form-control"
                 value="<?= htmlspecialchars($skill['rate_per_hr']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Level</label>
          <select name="level" class="form-select" required>
            <?php
              $levels = ['Beginner','Intermediate','Expert'];
              foreach ($levels as $lv) {
                $sel = ($skill['level'] === $lv) ? 'selected' : '';
                echo "<option $sel>" . htmlspecialchars($lv) . "</option>";
              }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Replace image (optional)</label>
          <input type="file" name="image" class="form-control">
          <?php if (!empty($skill['image_path'])): ?>
            <div class="form-text">Current: <?= htmlspecialchars($skill['image_path']) ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-brand">Update</button>
        <a href="details.php?id=<?= $skill_id ?>" class="btn btn-secondary ms-2">Cancel</a>
      </form>
    </main>

    <?php include 'includes/footer.inc'; ?>
  </body>
</html>
