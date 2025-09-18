<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SkillSwap - Home</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Ysabeau+SC&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />


    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
       <?php include 'includes/header.inc'; ?>

    <main class="container py-3">
        <h2 class="mb-4">Add New Skill</h2>
        <div class="row g-2">
            <div class="col-12 mb-2">
                <label for="title" class="form-label required">Title</label>
                <input id="title" name="title" type="text" class="form-control mb-3" placeholder="Enter skill title"
                    required>
            </div>
        </div>

        <div class="col-12 mb-2">

            <label for="Enter_Description" class="form-label required"> Description</label>
            <textarea id="Enter_Description" name="description" class="form-control textarea-lg"
                placeholder="Enter Description" rows="6" required></textarea>
        </div>

        <div class="col-md-6 mb-2">
            <label for="Category" class="form-label required">Category</label>
            <input id="Category" name="title" type="text" class="form-control mb-3" placeholder="Enter Skill Category"
                required>

        </div>
        <div class="col-md-6 mb-2">
            <label for="Rate" class="form-label required">Rate PER Hour($)</label>
            <input id="Rate" name="title" type="number" class="form-control mb-3" placeholder="Rate Per Hour" required>

        </div>
        <div class="col-md-6 mb-2">
            <label for="level" class="form-label required">Level</label>
            <select id="level" name="level" class="form-select" required>
                <option value="" disabled selected>Please select</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Expert">Expert</option>
            </select>
        </div>



        <div class="col-md-6 mb-2">
            <form id="addSkillForm"></form>
            <label for="image" class="form-label required">Skill Image</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <div class="col-md-6 mb-2">

            <button class="btn-brand" type="submit">Submit</button>
            <div id="imgMsg" style="color:#c00; margin-top:10px;"></div>
        </div>




    </main>
   <?php include 'includes/footer.inc'; ?>

    <script src="assets/js/script.js" defer></script>
</body>