<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<nav class="navbar navbar-expand-lg" style="background-color:#c84c0c; padding:6px 0;">
  <div class="container-fluid px-4 d-flex align-items-center">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="<?= $APP_BASE ?>/index.php" style="color:white; font-weight:600; font-size:1rem; text-transform:uppercase;">
      <img src="<?= $APP_BASE ?>/assets/images/SkillSwap_logo.png" alt="SkillSwap Logo" width="34" height="34" class="me-1">
      SkillSwap
    </a>

    <!-- Navbar toggle for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav links -->
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav ms-2">
        <li class="nav-item"><a class="nav-link text-white fw-semibold px-3" href="<?= $APP_BASE ?>/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold px-3" href="<?= $APP_BASE ?>/allskills.php" >All Skills</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold px-3" href="<?= $APP_BASE ?>/gallery.php" >Gallery</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold px-3" href="<?= $APP_BASE ?>/add.php" >Add Skill</a></li>
      </ul>

      <!-- Right Side -->
      <div class="d-flex align-items-center">
        <!-- Login/Logout Links -->
        <?php if (isset($_SESSION['username'])): ?>
          <span class="text-white fw-semibold me-3" style="font-size:0.9rem;">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
          <a href="<?= $APP_BASE ?>/logout.php" class="btn btn-outline-light btn-sm rounded-0 fw-semibold me-2" style="font-size:0.8rem; padding:3px 10px;">Logout</a>
        <?php else: ?>
          <a href="<?= $APP_BASE ?>/login.php" class="text-white fw-semibold me-3" style="text-decoration:none; font-size:0.9rem;">Login</a>
          <a href="<?= $APP_BASE ?>/register.php" class="text-white fw-semibold me-4" style="text-decoration:none; font-size:0.9rem;">Register</a>
        <?php endif; ?>

        <!-- Search -->
        <form class="d-flex" role="search">
          <div class="input-group">
            <span class="input-group-text bg-white border-0"><i class="material-icons" style="font-size:18px; color:#555;">search</i></span>
            <input class="form-control border-0" type="search" placeholder="Search skills..." aria-label="Search"
                   style="font-size:0.85rem; width:200px;">
          </div>
        </form>
      </div>
    </div>
  </div>
</nav>

<style>
  .nav-link:hover {
    text-decoration: underline;
    text-underline-offset: 3px;
  }
  .navbar-brand img {
    vertical-align: middle;
    filter: brightness(98%);
  }
</style>
