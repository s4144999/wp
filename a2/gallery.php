<?php /* /wp/a2/gallery.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/header.inc'; ?> <!-- ensure Bootstrap CSS is included here -->
</head>

<?php
include __DIR__ . '/includes/db_connect.inc';

$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // e.g. /wp/a2
$IMG_DIR  = '/wp/a2/assets/images/skills/';              // NOTE trailing slash

function resolve_skill_image_url(string $p): string {
    global $IMG_DIR;
    $p = trim($p);
    if ($p === '') return $IMG_DIR . 'default.png';
    if (preg_match('~^https?://~i', $p)) return $p; // full URL
    if ($p[0] === '/') return $p;                   // already absolute
    return $IMG_DIR . basename($p);
}

$sql = "SELECT skill_id, title, image_path
        FROM skills
        ORDER BY created_at DESC, skill_id DESC";
$result = $conn->query($sql);
?>

<body>
  <?php include 'includes/nav.inc'; ?>

  <main class="container py-4">
    <h2 class="mb-4">Skill Gallery</h2>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
          $id    = (int)$row['skill_id'];
          $title = htmlspecialchars($row['title']);
          $img   = resolve_skill_image_url((string)$row['image_path']);
        ?>
        <div class="col">
  <figure class="gallery-item m-0 text-center">
    <!-- Image opens modal -->
    <a href="<?= htmlspecialchars($img) ?>"
       data-bs-toggle="modal"
       data-bs-target="#lightboxModal"
       data-title="<?= $title ?>">
      <!-- fixed aspect ratio box -->
      <div class="ratio ratio-16x9 rounded overflow-hidden">
        <img src="<?= htmlspecialchars($img) ?>" class="w-100 h-100" style="object-fit:cover;" alt="<?= $title ?>">
      </div>
    </a>
    <!-- Title links to details page -->
    <figcaption class="mt-2">
      <a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>"><?= $title ?></a>
    </figcaption>
  </figure>
</div>

        <?php endwhile; ?>
      <?php else: ?>
        <p>No skills yet. <a href="<?= $APP_BASE ?>/add.php">Add one?</a></p>
      <?php endif; $conn->close(); ?>
    </div>
  </main>

  <!-- SIMPLE MODAL -->
  <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <img id="lightboxImg" src="" class="img-fluid rounded" alt="">
        <div id="lightboxTitle" class="p-2 fw-semibold"></div>
        <button type="button" class="btn btn-secondary m-2" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.inc'; ?>

  <!-- Minimal required JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  // modal: grab image + caption
  const imgEl = document.getElementById('lightboxImg');
  const titleEl = document.getElementById('lightboxTitle');

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-bs-target="#lightboxModal"]');
    if (!a) return;
    e.preventDefault();
    imgEl.src = a.getAttribute('href');
    imgEl.alt = a.dataset.title || '';
    titleEl.textContent = a.dataset.title || '';
  });
  </script>
</body>
</html>
