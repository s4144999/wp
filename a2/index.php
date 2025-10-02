<!DOCTYPE html>


<?php include 'includes/header.inc'; ?>  


<body>
    <?php include 'includes/nav.inc'; ?>

    <!-- ===== Main Content ===== -->
    <main class="container">
        <h2 class="mb-3">SkillSwap</h2>
        <p class="mb-4">Browse the latest skills shared by our community.</p>

<!-- ===== Carousel ===== -->
<div class="container-fluid px-0">
  <div id="skillCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      include __DIR__ . '/includes/db_connect.inc';


      function resolve_skill_image_url(string $dbValue): string {
          $clean = trim($dbValue);
          $clean = ltrim($clean, "/\\");                // remove leading slashes/backslashes

          // Prefer filename only
          $fileOnly = basename($clean);

          // Candidate web URLs (both current and legacy locations)
          $candidates = [
              "/wp/a2/assets/images/skills/{$fileOnly}", // current location
              "/wp/a2/assets/{$clean}",                  // if DB had "images/skills/2.png"
              "/wp/a2/images/skills/{$fileOnly}",        // legacy folder (if you used it earlier)
              "/wp/a2/{$clean}",                         // last-ditch: honor whatever is in DB under /wp/a2/
          ];

          foreach (array_unique($candidates) as $url) {
              // Map web URL to filesystem path relative to this file’s folder (/wp/a2)
              // e.g. "/wp/a2/assets/images/skills/2.png" -> "assets/images/skills/2.png"
              $relative = preg_replace('#^/wp/a2/#', '', $url);
              $fs = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
              if (file_exists($fs)) {
                  return $url;
              }
          }
          // If none exist, return the first candidate (so you can see which URL it's trying)
          return $candidates[0];
      }

      // Grab latest 5 skills for the carousel
      $sql = "SELECT skill_id, title, image_path
              FROM skills
              ORDER BY created_at DESC
              LIMIT 5";
      $result = $conn->query($sql);

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