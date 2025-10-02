<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'includes/header.inc'; ?>
</head>

<body>
<?php include 'includes/nav.inc'; ?>


    <main class="container mt-4">   
        <h2 class="mb-4">All Skills</h2>
        <div class="row g-4 align-items-start g-4 g-lg-5">
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <img src="assets/images/skills_banner.png" alt="Skills banner" class="img-fluid">
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
                                <td><a href="details.php?id=1">Beginner Guitar Lessons</a></td>
                                <td>Music</td>
                                <td>Beginner</td>
                                <td>30.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=2">Intermediate Fingerstyle</a></td>
                                <td>Music</td>
                                <td>Intermediate</td>
                                <td>45.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=3">Artisan Bread Baking</a></td>
                                <td>Cooking</td>
                                <td>Beginner</td>
                                <td>25.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=4">French Pastry Making</a></td>
                                <td>Cooking</td>
                                <td>Expert</td>
                                <td>50.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=5">Watercolor Basics</a></td>
                                <td>Art</td>
                                <td>Beginner</td>
                                <td>20.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=6">Digital Illustration with Procreate</a></td>
                                <td>Art</td>
                                <td>Intermediate</td>
                                <td>40.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=7">Morning Vinyasa Flow</a></td>
                                <td>Wellness</td>
                                <td>Intermediate</td>
                                <td>35.00</td>
                            </tr>
                            <tr>
                                <td><a href="details.php?id=8">Intro to PHP &amp; MySQL</a></td>
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

  

  <?php include 'includes/footer.inc'; ?>


</body>