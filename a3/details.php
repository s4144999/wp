<?php
include __DIR__ . '/includes/db_connect.inc';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

/* 1) Validate id */
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('Invalid request: No skill ID provided.');
}
$skill_id = (int)$_GET['id'];

/* 2) Fetch skill + instructor */
$sql = "SELECT s.*, u.username AS instructor_name, u.bio AS instructor_bio
        FROM skills s
        JOIN users u ON s.user_id = u.user_id
        WHERE s.skill_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit('DB prepare error: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param('i', $skill_id);
if (!$stmt->execute()) {
    http_response_code(500);
    exit('DB execute error: ' . htmlspecialchars($stmt->error));
}

/* Support mysqlnd and non-mysqlnd */
$skill = null;
if (method_exists($stmt, 'get_result')) {
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $skill = $result->fetch_assoc();
    }
} else {
    $stmt->bind_result(
        $s_skill_id, $s_user_id, $s_title, $s_desc, $s_cat, $s_img,
        $s_rate, $s_level, $s_created, $u_name, $u_bio
    );
    if ($stmt->fetch()) {
        $skill = [
            'skill_id'        => $s_skill_id,
            'user_id'         => $s_user_id,
            'title'           => $s_title,
            'description'     => $s_desc,
            'category'        => $s_cat,
            'image_path'      => $s_img,
            'rate_per_hr'     => $s_rate,
            'level'           => $s_level,
            'created_at'      => $s_created,
            'instructor_name' => $u_name,
            'instructor_bio'  => $u_bio,
        ];
    }
}
$stmt->close();

if (!$skill) {
    http_response_code(404);
    exit('Skill not found.');
}

/* 3) Only now is $skill safe to use */
$isOwner = isset($_SESSION['user_id']) &&
           ((int)$_SESSION['user_id'] === (int)$skill['user_id']);

/* 4) Paths */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';
$IMG_FS   = __DIR__ . '/assets/images/skills/';
$DEFAULT  = $IMG_WEB . 'default.png';

/* 5) Image URL */
$file   = basename(trim((string)$skill['image_path']));
$fsPath = $IMG_FS . $file;
$imgUrl = ($file !== '' && is_readable($fsPath))
        ? ($IMG_WEB . rawurlencode($file))
        : $DEFAULT;
?>

<!DOCTYPE html>
<html lang="en">
    <head>

  <!-- keep <link> and <style> here or in the include -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <?php include 'includes/header.inc'; ?>

</head>

<body>

  <?php include 'includes/nav.inc'; ?>

  <div class="container my-5">
    <h1 class="mb-4"><?= htmlspecialchars($skill['title']) ?></h1>

    <?php if (!empty($skill['image_path'])): ?>
      <a href="<?= htmlspecialchars($imgUrl) ?>"
         data-bs-toggle="modal"
         data-bs-target="#lightboxModal"
         data-title="<?= htmlspecialchars($skill['title']) ?>">
        <img src="<?= htmlspecialchars($imgUrl) ?>"
             alt="<?= htmlspecialchars($skill['title']) ?>"
             class="img-thumbnail mb-3"
             style="max-width:250px; cursor:pointer;">
      </a>
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($skill['description'])) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($skill['category']) ?></p>
    <p><strong>Level:</strong> <?= htmlspecialchars($skill['level']) ?></p>
    <p><strong>Rate:</strong> $<?= htmlspecialchars($skill['rate_per_hr']) ?>/hr</p>
    <hr>
    <p><strong>Created At:</strong> <?= htmlspecialchars($skill['created_at']) ?></p>
<p><strong>Instructor:</strong> 
  <a href="<?= $APP_BASE ?>/instructor.php?name=<?= urlencode($skill['instructor_name']) ?>">
    <?= htmlspecialchars($skill['instructor_name']) ?>
  </a>
</p>

<?php if ($isOwner): ?>
  <div class="mt-3 d-flex gap-2">
    <a href="<?= $APP_BASE ?>/edit.php?id=<?= (int)$skill['skill_id'] ?>"
       class="btn btn-warning btn-sm">Edit Skill</a>

    <form method="post" action="<?= $APP_BASE ?>/delete.php"
          onsubmit="return confirm('Delete this skill?');" class="d-inline">
      <input type="hidden" name="id" value="<?= (int)$skill['skill_id'] ?>">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
      <button type="submit" class="btn btn-danger btn-sm">Delete Skill</button>
    </form>
  </div>
<?php endif; ?>

<a href="<?= $APP_BASE ?>/index.php" class="btn btn-secondary mt-3">← Back to All Skills</a>

  </div>

  <!-- Lightbox Modal -->
  <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <img id="lightboxImg" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" class="img-fluid rounded" alt="Skill image">
        <p id="lightboxTitle" class="mt-2 fw-bold"></p>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.inc'; ?>

  <!-- Bootstrap bundle (required for modal) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Inline, tiny modal script (no external path headaches) -->
  <script>
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[data-bs-target="#lightboxModal"]');
      if (!a) return;
      e.preventDefault();
      const imgEl   = document.getElementById('lightboxImg');
      const titleEl = document.getElementById('lightboxTitle');
      imgEl.src = a.getAttribute('href');
      imgEl.alt = a.dataset.title || '';
      titleEl.textContent = a.dataset.title || '';
      
    });
  </script>
</body>
</html>
