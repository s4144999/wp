<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/header.inc'; ?>
</head>
<?php
// --- same setup/logic as index.php ---
include __DIR__ . '/includes/db_connect.inc';

$IMG_DIR = '/wp/a2/assets/images/skills'; // same base you use on index

function resolve_skill_image_url(string $p): string {
    global $IMG_DIR;
    $p = trim($p);
    if ($p === '') return $IMG_DIR . 'default.png';        // optional fallback
    if (preg_match('~^https?://~i', $p)) return $p;        // already full URL
    if ($p[0] === '/') return $p;                          // already root-absolute
    return rtrim($IMG_DIR, '/') . '/' . basename($p);      // filename -> /wp/a2/assets/images/filename
}

// pull everything (or add LIMIT / pagination if you want)
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
        $img   = resolve_skill_image_url((string)$row['image_path'], $IMG_DIR);
      ?>
      <div class="col">
        <a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>" class="text-decoration-none d-block">
          <figure class="gallery-item m-0">
            <img src="<?= htmlspecialchars($img) ?>" class="img-fluid rounded mb-2" alt="<?= $title ?>">
            <figcaption><?= $title ?></figcaption>
          </figure>
        </a>
        <!-- debug (remove after testing): -->
        <!-- Resolved: <?= htmlspecialchars($img) ?> -->
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No skills yet. <a href="<?= $APP_BASE ?>/add.php">Add one?</a></p>
    <?php endif; $conn->close(); ?>
  </div>
</main>


    <?php include 'includes/footer.inc'; ?>

    <script src="assets/js/script.js"></script>
</body>