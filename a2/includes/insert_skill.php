<?php
// include DB connection (correct path)
include __DIR__ . '/db_connect.inc';

// Form data
$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$category    = $_POST['category'] ?? '';
$rate        = $_POST['rate_per_hr'] ?? '';
$level       = $_POST['level'] ?? '';

// Image upload
$imageName = basename($_FILES['image']['name']);
$targetDir = __DIR__ . '/../assets/images/skills/';
$targetFile = $targetDir . $imageName;

// Make sure directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
    $imagePath = $imageName; // store only file name in DB

    $stmt = $conn->prepare("INSERT INTO skills (title, description, category, rate_per_hr, level, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $title, $description, $category, $rate, $level, $imagePath);

    if ($stmt->execute()) {
        header("Location: ../index.php");
        exit;
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error uploading image. Check folder permissions and path.";
}


$conn->close();
?>
