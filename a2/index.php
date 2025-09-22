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
                    <div class="carousel-item active">
                        <img src="images/skills/4.png" class="d-block w-100 vh-50 object-fit-cover"
                            alt=" French Pastry">
                        <div class="carousel-caption">
                            <h5>French Pastry Making</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/skills/3.png" class="d-block w-100 vh-50 object-fit-cover" alt=" Bread Baking">
                        <div class="carousel-caption">
                            <h5>Artisan Bread Baking</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/skills/8.png" class="d-block w-100 vh-50 object-fit-cover" alt=" PHP Skills">
                        <div class="carousel-caption">
                            <h5>Intro to PHP & MySQL</h5>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/skills/2.png" class="d-block w-100 vh-50 object-fit-cover" alt=" Guitar">
                        <div class="carousel-caption">
                            <h5>Intermediate Fingerstyle</h5>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#skillCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#skillCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        <!-- ===== Skill Grid ===== -->
        <!-- ===== Skill Grid (Dynamic from DB) ===== -->
        <?php
include __DIR__ . '/includes/db_connect.inc';

$sql = "SELECT title, rate_per_hr, image_path, description 
        FROM skills 
        ORDER BY created_at DESC 
        LIMIT 4";

$result = $conn->query($sql);

echo '<div class="row">';
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='col-md-3 col-sm-6 mb-4 skill-card'>
            <img src='" . htmlspecialchars($row['image_path']) . "' 
                 alt='" . htmlspecialchars($row['title']) . "' 
                 class='img-fluid mb-2'>
            <h5>" . htmlspecialchars($row['title']) . "</h5>
            <p>" . htmlspecialchars($row['description']) . "</p>
            <p>Rate: $" . htmlspecialchars($row['rate_per_hr']) . "/hr</p>
            <button class='btn'>View Details</button>
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