<?php /* /wp/a2/add.php */ ?>
<!DOCTYPE html>
<html lang="en">

  <?php include 'includes/header.inc'; ?>

<body>
<?php include 'includes/nav.inc'; ?>

<?php
include __DIR__ . '/includes/db_connect.inc';

$errors  = [];
$success = false;

// Filesystem directory where images are stored
$UPLOAD_FS = __DIR__ . DIRECTORY_SEPARATOR . 'assets'
           . DIRECTORY_SEPARATOR . 'images'
           . DIRECTORY_SEPARATOR . 'skills'
           . DIRECTORY_SEPARATOR;

// Generate safe unique name
function safe_upload_name(string $original): string {
  $ext  = strtolower(pathinfo($original, PATHINFO_EXTENSION));
  $base = preg_replace('~[^a-z0-9_-]+~i', '-', pathinfo($original, PATHINFO_FILENAME));
  $base = trim($base, '-');
  if ($base === '') $base = 'upload';
  return uniqid('skill_', true) . '-' . $base . ($ext ? ".$ext" : '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $title       = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $category    = trim($_POST['category'] ?? '');
  $level       = $_POST['level'] ?? '';
  $rate        = $_POST['rate'] ?? '';

  if ($title === '')        $errors[] = 'Title is required.';
  if ($description === '')  $errors[] = 'Description is required.';
  if ($category === '')     $errors[] = 'Category is required.';
  if ($level === '')        $errors[] = 'Level is required.';
  if ($rate === '' || !is_numeric($rate) || (float)$rate < 0) {
    $errors[] = 'Rate must be a positive number.';
  } else {
    $rate = (float)$rate;
  }

  $newName = null;
  if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = 'Image upload failed.';
  } else {
    $allowedExt  = ['jpg','jpeg','png','gif','webp'];
    $allowedMime = ['image/jpeg','image/png','image/gif','image/webp'];

    $fileName = $_FILES['image']['name'];
    $fileTmp  = $_FILES['image']['tmp_name'];
    $fileSize = (int)$_FILES['image']['size'];
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt, true)) {
      $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedExt) . '.';
    }
    if ($fileSize > 5 * 1024 * 1024) {
      $errors[] = 'File too large. Max 5MB allowed.';
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = $finfo ? finfo_file($finfo, $fileTmp) : '';
    if ($finfo) finfo_close($finfo);
    if ($mime && !in_array($mime, $allowedMime, true)) {
      $errors[] = 'Invalid image content (MIME).';
    }

    if (!$errors) {
      if (!is_dir($UPLOAD_FS) && !mkdir($UPLOAD_FS, 0755, true)) {
        $errors[] = 'Server error: cannot create upload directory.';
      } else {
        $newName = safe_upload_name($fileName);
        $destAbs = $UPLOAD_FS . $newName;
        if (!move_uploaded_file($fileTmp, $destAbs)) {
          $errors[] = 'Failed to save uploaded image.';
        }
      }
    }
  }

  if (!$errors && $newName !== null) {
    $sql  = "INSERT INTO skills (title, description, category, rate_per_hr, level, image_path)
             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      $errors[] = 'Database error (prepare): ' . $conn->error;
    } else {
      // types: s s s d s s  -> WITHOUT SPACES: 'sss dss' -> correct is:
      $stmt->bind_param('sssdss', $title, $description, $category, $rate, $level, $newName); // placeholder line

      // Correct final line (no spaces at all):
      $stmt->bind_param('sssdss', $title, $description, $category, $rate, $level, $newName);
    }

    // The exact correct string is 'sss dss' without spaces -> 'sssdss'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdss', $title, $description, $category, $rate, $level, $newName);

    if ($stmt->execute()) {
      $success = true;
    } else {
      $errors[] = 'Database error (execute): ' . $stmt->error;
      // Optional rollback of file:
      @unlink($UPLOAD_FS . $newName);
    }
    $stmt->close();
  }
}
?>

<main class="container py-3">
  <h2 class="mb-4">Add New Skill</h2>

  <?php if ($success): ?>
    <div class="alert alert-success">Skill added successfully!
      <a class="ms-2" href="index.php">Home</a>
      <a class="ms-2" href="gallery.php">Gallery</a>
    </div>
  <?php elseif ($errors): ?>
    <?php foreach ($errors as $e): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label required">Title</label>
      <input id="title" name="title" type="text" class="form-control"
             value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label required">Description</label>
      <textarea id="description" name="description" class="form-control" rows="5" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label required">Category</label>
      <input id="category" name="category" type="text" class="form-control"
             value="<?= htmlspecialchars($_POST['category'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="rate" class="form-label required">Rate PER Hour ($)</label>
      <input id="rate" name="rate" type="number" step="0.01" class="form-control"
             value="<?= htmlspecialchars($_POST['rate'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="level" class="form-label required">Level</label>
      <select id="level" name="level" class="form-select" required>
        <option value="" disabled <?= empty($_POST['level']) ? 'selected' : '' ?>>Select Level</option>
        <?php
          foreach (['Beginner','Intermediate','Expert'] as $lv) {
            $sel = (($_POST['level'] ?? '') === $lv) ? 'selected' : '';
            echo "<option $sel>" . htmlspecialchars($lv) . "</option>";
          }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="image" class="form-label required">Skill Image</label>
      <input type="file" id="image" name="image" class="form-control" required>
    </div>

    <div class="mb-3">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</main>

<?php include 'includes/footer.inc'; ?>
<script src="assets/js/scripts.js" defer></script>
</body>
</html>
