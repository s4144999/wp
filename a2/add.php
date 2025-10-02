<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.inc'; ?>
</head>
<body>
   <?php /* /wp/a2/add.php */ 
// ---------- Common helpers (inline so this file is standalone) ----------
$APP_BASE  = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');       // e.g. /~id/wp/a2 or /wp/a2
$IMG_DIR   = $APP_BASE . '/assets/images/skills/';                // web path for <img src>
$UPLOAD_DIR = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $IMG_DIR;   // filesystem path for uploads

// Very small helper: safe basename
function clean_filename(string $name): string {
    // keep extension, clean rest, prepend timestamp to avoid collisions
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $base = preg_replace('~[^a-z0-9_-]+~i', '-', pathinfo($name, PATHINFO_FILENAME));
    $base = trim($base, '-');
    if ($base === '') { $base = 'upload'; }
    return date('Ymd-His') . '-' . $base . ($ext ? ".$ext" : '');
}

// ---------- Handle POST (process + insert) ----------
$errors = [];
$done   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Basic server-side validation
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $level       = trim($_POST['level'] ?? '');
    $rate        = $_POST['rate_per_hr'] ?? '';

    if ($title === '')        $errors[] = 'Title is required.';
    if ($description === '')  $errors[] = 'Description is required.';
    if ($category === '')     $errors[] = 'Category is required.';
    if ($level === '')        $errors[] = 'Level is required.';
    if ($rate === '' || !is_numeric($rate) || (float)$rate < 0) {
        $errors[] = 'Rate per hour must be a positive number.';
    } else {
        $rate = (float)$rate;
    }

    // 2) Image checks (required)
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Image is required.';
    } else {
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);
        $allowed = ['image/png','image/jpeg','image/jpg','image/gif','image/webp'];
        if (!in_array($mime, $allowed, true)) {
            $errors[] = 'Unsupported image type. Please upload PNG/JPG/GIF/WEBP.';
        }
    }

    // 3) If no errors, upload and insert
    if (!$errors) {
        // Make sure upload dir exists
        if (!is_dir($UPLOAD_DIR) && !mkdir($UPLOAD_DIR, 0755, true)) {
            $errors[] = 'Server error: cannot create upload directory.';
        } else {
            $safeName = clean_filename($_FILES['image']['name']);
            $destAbs  = $UPLOAD_DIR . $safeName;  // filesystem
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $destAbs)) {
                $errors[] = 'Server error: failed to save uploaded image.';
            } else {
                // Store only the filename in DB
                $imagePath = $safeName;

                // Insert into DB
                require_once __DIR__ . '/includes/db_connect.inc'; // sets $conn

                $sql  = "INSERT INTO skills
                         (title, description, category, rate_per_hr, level, image_path)
                         VALUES (?, ?, ?, ?, ?, ?)";   // skill_id is AUTO_INCREMENT
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    $errors[] = 'Database error (prepare): ' . $conn->error;
                } else {
                    // types: s s s d s s
                    $stmt->bind_param('sssdss', $title, $description, $category, $rate, $level, $imagePath);
                    if ($stmt->execute()) {
                        $done = true;
                        // Optionally get new id: $newId = $stmt->insert_id;
                    } else {
                        $errors[] = 'Database error (execute): ' . $stmt->error;
                        // roll back file if insert failed
                        @unlink($destAbs);
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.inc'; ?>
</head>
<body>
<?php include 'includes/nav.inc'; ?>

<main class="container py-4">
    <h2 class="mb-4">Add New Skill</h2>

    <!-- Alerts -->
    <?php if ($done): ?>
        <div class="alert alert-success">Skill added successfully.
            <a class="ms-2" href="index.php">Back to Home</a>
            <a class="ms-2" href="gallery.php">Go to Gallery</a>
        </div>
    <?php elseif ($errors): ?>
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form (sticky values on error) -->
    <form action="" method="post" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="title" class="form-label required">Title</label>
            <input id="title" name="title" type="text" class="form-control"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label required">Description</label>
            <textarea id="description" name="description" class="form-control" rows="6" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label required">Category</label>
            <input id="category" name="category" type="text" class="form-control"
                   value="<?= htmlspecialchars($_POST['category'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="rate_per_hr" class="form-label required">Rate per hour ($)</label>
            <input id="rate_per_hr" name="rate_per_hr" type="number" step="0.01" min="0"
                   class="form-control" value="<?= htmlspecialchars($_POST['rate_per_hr'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="level" class="form-label required">Level</label>
            <select id="level" name="level" class="form-select" required>
                <option value="" disabled <?= empty($_POST['level']) ? 'selected' : '' ?>>Please select</option>
                <?php
                $levels = ['Beginner','Intermediate','Expert'];
                $curLvl = $_POST['level'] ?? '';
                foreach ($levels as $lv) {
                    $sel = ($curLvl === $lv) ? 'selected' : '';
                    echo "<option $sel>" . htmlspecialchars($lv) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label required">Skill Image</label>
            <input type="file" id="image" name="image" class="form-control" <?= $done ? '' : 'required' ?>>
        </div>

        <button type="submit" class="btn btn-brand">Submit</button>
    </form>
</main>

<?php include 'includes/footer.inc'; ?>
</body>
</html>