<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.inc'; ?>
</head>
<body>
    <?php include 'includes/nav.inc'; ?>

    <main class="container py-3">
        <h2 class="mb-4">Add New Skill</h2>

        <!-- Proper form -->
            <form action="includes/insert_skill.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label required">Title *</label>
                <input id="title" name="title" type="text" class="form-control" placeholder="Enter skill title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label required">Description *</label>
                <textarea id="description" name="description" class="form-control" rows="6" placeholder="Enter Description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label required">Category *</label>
                <input id="category" name="category" type="text" class="form-control" placeholder="Enter Skill Category" required>
            </div>

            <div class="mb-3">
                <label for="rate_per_hr" class="form-label required">Rate PER Hour($) *</label>
                <input id="rate_per_hr" name="rate_per_hr" type="number" class="form-control" placeholder="Rate Per Hour" required>
            </div>

            <div class="mb-3">
                <label for="level" class="form-label required">Level *</label>
                <select id="level" name="level" class="form-select" required>
                    <option value="" disabled selected>Please select</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label required">Skill Image *</label>
                <input type="file" id="image" name="image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-brand">Submit</button>
        </form>
    </main>

    <?php include 'includes/footer.inc'; ?>
</body>
</html>
