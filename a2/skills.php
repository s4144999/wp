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


    <main class="container mt-4">
        <h2 class="mb-4">All Skills</h2>
        <div class="row g-4 align-items-start g-4 g-lg-5">
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <img src="images/skills_banner.png" alt="Skills banner" class="img-fluid">
            </div>
            <div class="col-12 col-lg-7 ps-lg-4s">
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle mb-0 w-100 skills-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Rate ($/hr)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Beginner Guitar Lessons</td>
                                <td>Music</td>
                                <td>Beginner</td>
                                <td>30.00</td>
                            </tr>
                            <tr>
                                <td>Intermediate Fingerstyle</td>
                                <td>Music</td>
                                <td>Intermediate</td>
                                <td>45.00</td>
                            </tr>
                            <tr>
                                <td>Artisan Bread Baking</td>
                                <td>Cooking</td>
                                <td>Beginner</td>
                                <td>25.00</td>
                            </tr>
                            <tr>
                                <td>French Pastry Making</td>
                                <td>Cooking</td>
                                <td>Expert</td>
                                <td>50.00</td>
                            </tr>
                            <tr>
                                <td>Watercolor Basics</td>
                                <td>Art</td>
                                <td>Beginner</td>
                                <td>20.00</td>
                            </tr>
                            <tr>
                                <td>Digital Illustration with Procreate</td>
                                <td>Art</td>
                                <td>Intermediate</td>
                                <td>40.00</td>
                            </tr>
                            <tr>
                                <td>Morning Vinyasa Flow</td>
                                <td>Wellness</td>
                                <td>Intermediate</td>
                                <td>35.00</td>
                            </tr>
                            <tr>
                                <td>Intro to PHP &amp; MySQL</td>
                                <td>Programming</td>
                                <td>Expert</td>
                                <td>55.00</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!---->

  <?php include 'includes/footer.inc'; ?>


</body>