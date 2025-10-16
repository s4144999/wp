<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'includes/header.inc'; ?>
</head>

<body>
<?php include 'includes/nav.inc'; ?>


    <main class="container mt-4">
  <h2 class="mb-4">All Skills</h2>

  <div class="row g-4 align-items-start g-lg-5">
    <div class="col-12 col-lg-5 mb-4 mb-lg-0">
      <img src="assets/images/skills_banner.png" alt="Skills banner" class="img-fluid">
    </div>

    <div class="col-12 col-lg-7 ps-lg-4">
      <div class="table-responsive mt-3">
        <table class="table table-hover align-middle mb-0 w-100 skills-table">
          <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Level</th>
            <th class="text-end">Rate ($/hr)</th>
          </tr>
          </thead>
          <tbody>
          <?php
          // --- DB ---
          include __DIR__ . '/includes/db_connect.inc';

          // Use your column names. Assumes: skill_id, title, category, level, rate_per_hr, created_at
          $sql = "SELECT skill_id, title, category, level, rate_per_hr
                  FROM skills
                  ORDER BY created_at DESC, skill_id DESC";
          $res = $conn->query($sql);

          if ($res && $res->num_rows > 0):
            while ($row = $res->fetch_assoc()):
              $id    = (int)$row['skill_id'];
              $title = htmlspecialchars($row['title']);
              $cat   = htmlspecialchars($row['category']);
              $level = htmlspecialchars($row['level']);
              // format numeric rate safely
              $rate  = is_numeric($row['rate_per_hr']) ? number_format((float)$row['rate_per_hr'], 2) : htmlspecialchars((string)$row['rate_per_hr']);
          ?>
            <tr>
              <td><a href="details.php?id=<?= $id ?>"><?= $title ?></a></td>
              <td><?= $cat ?></td>
              <td><?= $level ?></td>
              <td class="text-end"><?= $rate ?></td>
            </tr>
          <?php
            endwhile;
          else:
          ?>
            <tr>
              <td colspan="4" class="text-muted">No skills available yet.</td>
            </tr>
          <?php
          endif;
          $conn->close();
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

  

  <?php include 'includes/footer.inc'; ?>


</body>