<?php
include __DIR__ . '/includes/db_connect.inc';

// Validate ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    exit('Invalid request: No skill ID provided.');
}
$skill_id = (int)$_GET['id'];

// Fetch skill
$sql = "SELECT * FROM skills WHERE skill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    exit('Skill not found.');
}
$skill = $result->fetch_assoc();
$stmt->close();
$conn->close();

/* ---------- Paths that work on localhost and Titan ---------- */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');      // e.g. /wp/a2  or  /~s4144999/wp/a2
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';              // web URL for <img src>
$IMG_FS   = __DIR__ . '/assets/images/skills/';                // filesystem path
$DEFAULT  = $IMG_WEB . 'default.png';

/* Build final image URL safely */
$file   = basename(trim((string)$skill['image_path']));        // "1.png" (strip spaces/paths)
$fsPath = $IMG_FS . $file;
$imgUrl = (is_readable($fsPath) && $file !== '')
        ? ($IMG_WEB . rawurlencode($file))
        : $DEFAULT;
?>
<!DOCTYPE html>
<html lang="en">

  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($skill['title']) ?> - Skill Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
  <?php include 'includes/header.inc'; ?>
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
    <p><strong>Created At:</strong> <?= htmlspecialchars($skill['created_at']) ?></p>

    <a href="<?= $APP_BASE ?>/index.php" class="btn btn-secondary mt-3">← Back to All Skills</a>
  </div>

  <!-- Lightbox Modal -->
  <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <img id="lightboxImg" src="" class="img-fluid rounded" alt="Skill image">
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
