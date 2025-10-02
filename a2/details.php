<?php
include __DIR__ . '/includes/db_connect.inc';

    

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request: No skill ID provided.");
}

$skill_id = (int)$_GET['id'];

// Fetch skill from DB
$sql = "SELECT * FROM skills WHERE skill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $skill_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $skill = $result->fetch_assoc();
} else {
    die("Skill not found.");
}

$stmt->close();
$conn->close();

// Define correct image path
$IMG_DIR = '/wp/a2/assets/images/skills/';
$imgUrl  = $IMG_DIR . basename($skill['image_path']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($skill['title']); ?> - Skill Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'includes/header.inc'; ?>  
    <?php include 'includes/nav.inc'; ?>  

    <div class="container my-5">
        <h1 class="mb-4"><?php echo htmlspecialchars($skill['title']); ?></h1>

        <?php if (!empty($skill['image_path'])): ?>
    <a href="<?php echo $imgUrl; ?>"
       data-bs-toggle="modal"
       data-bs-target="#lightboxModal"
       data-title="<?php echo htmlspecialchars($skill['title']); ?>">
        <img src="<?php echo $imgUrl; ?>" 
             alt="<?php echo htmlspecialchars($skill['title']); ?>" 
             class="img-thumbnail mb-3"
             style="max-width:250px;6dz cursor:pointer;">
    </a>
<?php endif; ?> 

        <p><?php echo htmlspecialchars($skill['description']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($skill['category']); ?></p>
        <p><strong>Level:</strong> <?php echo htmlspecialchars($skill['level']); ?></p>
        <p><strong>Rate:</strong> $<?php echo htmlspecialchars($skill['rate_per_hr']); ?>/hr</p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($skill['created_at']); ?></p>

        <a href="index.php" class="btn btn-secondary mt-3">← Back to All Skills</a>
    </div>

    <!-- Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <img id="lightboxImg" src="" class="img-fluid rounded" alt="Skill image">
                <p id="lightboxTitle" class="mt-2 fw-bold"></p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.inc'; ?> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/wp/a2/assets/js/scripts.js"></script>  
</body>
</html>
