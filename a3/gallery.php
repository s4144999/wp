<?php /* /wp/a3/gallery.php */ ?>
<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<?php
include __DIR__ . '/includes/db_connect.inc';

/* ---- Setup paths ---- */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';
$IMG_FS   = __DIR__ . '/assets/images/skills/';
$DEFAULT  = $IMG_WEB . 'default.png';

/* Safe image URL */
function skill_img_url(string $p): string {
  global $IMG_WEB, $IMG_FS, $DEFAULT;
  $file = basename(trim($p));
  if ($file === '') return $DEFAULT;
  $fsPath = $IMG_FS . $file;
  return is_readable($fsPath) ? ($IMG_WEB . rawurlencode($file)) : $DEFAULT;
}

/* Get all skills */
$sql = "SELECT skill_id, title, category, image_path
        FROM skills
        ORDER BY created_at DESC, skill_id DESC";
$result = $conn->query($sql);

/* Get unique categories */
$catRes = $conn->query("SELECT DISTINCT category FROM skills ORDER BY category ASC");
$categories = [];
if ($catRes && $catRes->num_rows > 0) {
  while ($row = $catRes->fetch_assoc()) {
    $categories[] = htmlspecialchars($row['category']);
  }
}
?>

<body>
<?php include 'includes/nav.inc'; ?>

<main class="container py-4">
  <h2 class="mb-4" style="color:#cd4f07;">Skill Gallery</h2>

  <!-- Category Filter -->
  <div class="mb-4">
    <label for="filter" class="form-label fw-semibold">Filter by Category</label>
    <select id="filter" class="form-select" style="max-width:300px;">
      <option value="all" selected>All</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat ?>"><?= $cat ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Gallery grid -->
  <div id="galleryGrid" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()):
        $id    = (int)$row['skill_id'];
        $title = htmlspecialchars($row['title']);
        $cat   = htmlspecialchars($row['category']);
        $img   = skill_img_url((string)$row['image_path']);
      ?>
        <div class="col gallery-item" data-category="<?= strtolower($cat) ?>">
          <figure class="text-center m-0">
            <a href="<?= htmlspecialchars($img) ?>"
               data-bs-toggle="modal"
               data-bs-target="#lightboxModal"
               data-title="<?= $title ?>">
              <div class="ratio ratio-1x1 rounded overflow-hidden">
                <img src="<?= htmlspecialchars($img) ?>"
                     class="w-100 h-100"
                     style="object-fit:cover;"
                     alt="<?= $title ?>">
              </div>
            </a>
            <figcaption class="mt-2">
              <a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>"><?= $title ?></a>
            </figcaption>
          </figure>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No skills available yet.</p>
    <?php endif; ?>
  </div>
</main>

<!-- Image Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
<!-- 1×1 transparent GIF placeholder keeps validators happy -->
<img
  id="lightboxImg"
  src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
  class="img-fluid rounded-top"
  alt=""
  width="1" height="1"
/>
      <div id="lightboxTitle" class="p-2 fw-semibold"></div>
      <button type="button" class="btn btn-secondary m-2" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>

<?php include 'includes/footer.inc'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Modal preview logic
  const imgEl = document.getElementById('lightboxImg');
  const titleEl = document.getElementById('lightboxTitle');
  document.addEventListener('click', e => {
    const a = e.target.closest('a[data-bs-target="#lightboxModal"]');
    if (!a) return;
    e.preventDefault();
    imgEl.src = a.getAttribute('href');
    titleEl.textContent = a.dataset.title || '';
  });

  // Filter by category
  const filter = document.getElementById('filter');
  const items = document.querySelectorAll('.gallery-item');
  filter.addEventListener('change', () => {
    const selected = filter.value.toLowerCase();
    items.forEach(el => {
      const cat = el.dataset.category;
      el.style.display = (selected === 'all' || cat === selected) ? '' : 'none';
    });
  });
</script>
</body>
</html>
