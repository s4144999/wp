  <!DOCTYPE html>


  <?php include 'includes/header.inc'; ?>  


  <body>
      <?php include 'includes/nav.inc'; ?>

      <!-- ===== Main Content ===== -->
      <main class="container">
          <h2 class="mb-3">SkillSwap</h2>
          <p class="mb-4">Browse the latest skills shared by our community.</p>

  <!-- ===== Carousel ===== -->
 <?php
include __DIR__ . '/includes/db_connect.inc';

// Web base for this app (works on localhost and Titan/Jacob)
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');              // e.g. /wp/a2  or  /~s4144999/wp/a2
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';                      // web path used in <img src>
$IMG_FS   = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $IMG_WEB;          // filesystem path for existence check
$DEFAULT  = $IMG_WEB . 'default.png';                                  // fallback image

// Latest 5 skills
$sql = "SELECT skill_id, title, image_path
        FROM skills
        ORDER BY created_at DESC, skill_id DESC
        LIMIT 5";
$res = $conn->query($sql);
?>

<div class="container-fluid px-0">
  <div id="skillCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php if ($res && $res->num_rows > 0):
        $active = 'active';
        while ($row = $res->fetch_assoc()):
          $id    = (int)$row['skill_id'];
          $title = htmlspecialchars($row['title']);
          $file  = basename((string)$row['image_path']);                 // make sure it's just a filename
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


        if ($result && $result->num_rows > 0) {
            $isActive = true;
            while ($row = $result->fetch_assoc()) {
                $id    = (int)$row['skill_id'];
                $title = htmlspecialchars($row['title']);
                $img   = resolve_skill_image_url((string)$row['image_path']);

                echo '
                <div class="carousel-item ' . ($isActive ? 'active' : '') . '">
                  <a href="details.php?id=' . $id . '">
                    <img src="' . htmlspecialchars($img) . '" class="d-block w-100 vh-50 object-fit-cover" alt="' . $title . '">
                    <div class="carousel-caption">
                      <h5>' . $title . '</h5>
                    </div>
                  </a>
                </div>';
                $isActive = false;
            }
        } else {
            echo '<p class="text-center my-4">No skills available yet.</p>';
        }
        ?>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#skillCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#skillCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>

          <!-- ===== Skill Grid ===== -->
          <?php
          include __DIR__ . '/includes/db_connect.inc';

          $sql = "SELECT skill_id, title, description, rate_per_hr, image_path 
          FROM skills 
          ORDER BY created_at DESC 
          LIMIT 4";
          $result = $conn->query($sql);


  echo '<div class="row">';
  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $id = (int)$row['skill_id']; // now guaranteed from query

          echo "
          <div class='col-md-3 col-sm-6 mb-4 skill-card'>
              <h5>" . htmlspecialchars($row['title']) . "</h5>
            <p>Rate: $" . htmlspecialchars($row['rate_per_hr']) . "/hr</p>
              <a href='details.php?id={$id}' class='btn btn-primary'>View Details</a>
          </div>";
      }
  } else {
      echo "<p>No skills available yet.</p>";
  }
  echo '</div>';



  $conn->close();
  ?>

          </div>
      </main>

  <?php include 'includes/footer.inc'; ?>

  </body>

  </html>