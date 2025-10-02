<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/header.inc'; ?>
</head>
<body>
<?php include 'includes/nav.inc'; ?>

<main class="container">
  <h2 class="mb-3">SkillSwap</h2>
  <p class="mb-4">Browse the latest skills shared by our community.</p>

  <?php
  // DB once
  include __DIR__ . '/includes/db_connect.inc';

  // App base, image paths
  $APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');           // e.g. /wp/a2 or /~id/wp/a2
  $IMG_WEB  = $APP_BASE . '/assets/images/skills/';                   // web path in <img src>
  $IMG_FS   = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $IMG_WEB;       // filesystem path for check
  $DEFAULT  = $IMG_WEB . 'default.png';

  // Latest 5 for the carousel
  $res = $conn->query("
    SELECT skill_id, title, image_path
    FROM skills
    ORDER BY created_at DESC, skill_id DESC
    LIMIT 5
  ");
  ?>

  <!-- ===== Carousel ===== -->
  <div class="container-fluid px-0">
    <div id="skillCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php if ($res && $res->num_rows > 0): 
          $active = 'active';
          while ($row = $res->fetch_assoc()):
            $id    = (int)$row['skill_id'];
            $title = htmlspecialchars($row['title']);
            $file  = basename((string)$row['image_path']);
            $src   = is_file($IMG_FS . $file) ? ($IMG_WEB . $file) : $DEFAULT;
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
