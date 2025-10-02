<!DOCTYPE html>
<html lang="en">

  <?php include 'includes/header.inc'; ?>

<body>
<?php include 'includes/nav.inc'; ?>

<main class="container">
  <h2 class="mb-3">SkillSwap</h2>
  <p class="mb-4">Browse the latest skills shared by our community.</p>

  <?php
include __DIR__ . '/includes/db_connect.inc';

$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');      // /~s4144999/wp/a2
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';              // URL for <img src>
$IMG_FS   = __DIR__ . '/assets/images/skills/';                // DISK path
$DEFAULT  = $IMG_WEB . '1.png';

$res = $conn->query("
  SELECT skill_id, title, image_path
  FROM skills
  ORDER BY created_at DESC, skill_id DESC
  LIMIT 5
");
?>
<div class="container-fluid px-0">
  <div id="skillCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php if ($res && $res->num_rows > 0):
        $active = 'active';
        while ($row = $res->fetch_assoc()):
          $id    = (int)$row['skill_id'];
          $title = htmlspecialchars($row['title']);

          // --- sanitize the DB value ---
          $file  = basename(trim((string)$row['image_path'])); // "1.png" (strip spaces/paths)
          $fs    = $IMG_FS . $file;
          $src   = $IMG_WEB . rawurlencode($file);             // encode spaces etc.

          // If disk file not readable, fall back
          if (!is_readable($fs)) {
            // optional: log to PHP error log so you can see what's wrong
            // error_log("Carousel image missing or unreadable: $fs");
            $src = $DEFAULT;
          }
      ?>
        <div class="carousel-item <?= $active ?>">
          <a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>">
            <img src="<?= htmlspecialchars($src) ?>" class="d-block w-100 vh-50 object-fit-cover" alt="<?= $title ?>">
            <div class="carousel-caption">
              <h5><?= $title ?></h5>
            </div>
          </a>
        </div>
      <?php $active = ''; endwhile; else: ?>
        <p class="text-center my-4">No skills available yet.</p>
      <?php endif; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#skillCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#skillCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>


  <!-- ===== Latest 4 skill cards ===== -->
  <?php
  $grid = $conn->query("
    SELECT skill_id, title, rate_per_hr
    FROM skills
    ORDER BY created_at DESC, skill_id DESC
    LIMIT 4
  ");
  ?>

  <div class="row">
    <?php if ($grid && $grid->num_rows > 0): 
      while ($row = $grid->fetch_assoc()):
        $id    = (int)$row['skill_id'];
        $title = htmlspecialchars($row['title']);
        $rate  = htmlspecialchars($row['rate_per_hr']);
    ?>
      <div class="col-md-3 col-sm-6 mb-4 skill-card">
        <h5><?= $title ?></h5>
        <p>Rate: $<?= $rate ?>/hr</p>
        <a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>" class="btn btn-primary">View Details</a>
      </div>
    <?php endwhile; else: ?>
      <p>No skills available yet.</p>
    <?php endif; ?>
  </div>

  <?php $conn->close(); ?>
</main>

<?php include 'includes/footer.inc'; ?>
</body>
</html>
