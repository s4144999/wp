<?php
include __DIR__ . '/includes/db_connect.inc';

/* ----------- Validate and sanitize instructor name ----------- */
if (!isset($_GET['name']) || empty($_GET['name'])) {
    exit('Invalid request: No instructor name provided.');
}
$username = trim($_GET['name']);

/* ----------- Fetch instructor info ----------- */
$stmt = $conn->prepare("SELECT user_id, username, bio FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$instructorRes = $stmt->get_result();

if ($instructorRes->num_rows === 0) {
    exit('Instructor not found.');
}
$instructor = $instructorRes->fetch_assoc();
$user_id = (int)$instructor['user_id'];
$stmt->close();

/* ----------- Fetch instructor's skills ----------- */
$sql = "SELECT skill_id, title, image_path, rate_per_hr 
        FROM skills 
        WHERE user_id = ?
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skillsRes = $stmt->get_result();

/* ----------- Image paths ----------- */
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$IMG_WEB  = $APP_BASE . '/assets/images/skills/';
$IMG_FS   = __DIR__ . '/assets/images/skills/';
$DEFAULT  = $IMG_WEB . 'default.png';

/* Helper: build image URL */
function skill_img_url(string $p): string {
    global $IMG_WEB, $IMG_FS, $DEFAULT;
    $file = basename(trim($p));
    if ($file === '') return $DEFAULT;
    return is_readable($IMG_FS . $file)
        ? ($IMG_WEB . rawurlencode($file))
        : $DEFAULT;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/header.inc'; ?>
</head>
<body>
  <?php include 'includes/nav.inc'; ?>

  <div class="container my-5">
    <!-- Instructor Info -->
    <h2 class="fw-bold mb-2" style="color:#cd4f07;">Instructor: <?= htmlspecialchars($instructor['username']) ?></h2>
    <p><?= htmlspecialchars($instructor['bio']) ?></p>

    <h3 class="mt-4 mb-4 fw-bold" style="color:#cd4f07;">Skills Offered</h3>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php if ($skillsRes && $skillsRes->num_rows > 0): ?>
        <?php while ($skill = $skillsRes->fetch_assoc()): 
          $id    = (int)$skill['skill_id'];
          $title = htmlspecialchars($skill['title']);
          $rate  = htmlspecialchars($skill['rate_per_hr']);
          $img   = skill_img_url($skill['image_path']);
        ?>
        <div class="col">
          <div class="card border-0 text-center bg-light">
            <img src="<?= $img ?>" class="card-img-top rounded" alt="<?= $title ?>" style="object-fit:cover; height:230px;">
            <div class="card-body">
              <h6 class="fw-semibold"><a href="<?= $APP_BASE ?>/details.php?id=<?= $id ?>" class="text-decoration-none text-dark"><?= $title ?></a></h6>
              <p class="mb-1">Rate: $<?= $rate ?>/hr</p>
<a href="details.php?id=<?= $id ?>" 
   style="background-color:#c84c0c; color:#fff; border:none; border-radius:30px; 
          padding:4px 14px; font-size:0.8rem; font-weight:600; 
          text-transform:uppercase; text-decoration:none; display:inline-block;">
   View
</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No skills available for this instructor.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include 'includes/footer.inc'; ?>
</body>
</html>
