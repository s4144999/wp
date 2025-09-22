<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/header.inc'; ?>
</head>

<body>
    <?php include 'includes/nav.inc'; ?>

    <main class="container py-4">
        <h2 class="mb-4">Skill Gallery</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="galleryGrid">
            <div class="col">
                <a href="images/skills/1.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Beginner Guitar Lessons">
                    <figure class="gallery-item">
                        <img src="images/skills/1.png" class="img-fluid" alt="Beginner Guitar Lessons">
                        <figcaption>Beginner Guitar Lessons</figcaption>
                    </figure>
                </a>
            </div>

            <div class="col">
                <a href="images/skills/2.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Intermediate Fingerstyle">
                    <figure class="gallery-item">
                        <img src="images/skills/2.png" class="img-fluid" alt="Intermediate Fingerstyle">
                        <figcaption>Intermediate Fingerstyle</figcaption>
                    </figure>
                </a>
            </div>

            <div class="col">
                <a href="images/skills/3.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Artgan Bread Making">
                    <figure class="gallery-item">
                        <img src="images/skills/3.png" class="img-fluid" alt="Artgan Bread Making">
                        <figcaption>Artgan Bread Making</figcaption>
                    </figure>
                </a>
            </div>

            <div class="col">
                <a href="images/skills/4.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="French Pastry Making">
                    <figure class="gallery-item">
                        <img src="images/skills/4.png" class="img-fluid" alt="French Pastry Making">
                        <figcaption>French Pastry Making</figcaption>
                    </figure>
                </a>
            </div>
            <div class="col">
                <a href="images/skills/5.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="WaterColor Basics">
                    <figure class="gallery-item">
                        <img src="images/skills/5.png" class="img-fluid" alt="WaterColor Basics">
                        <figcaption>WaterColor Basics</figcaption>
                    </figure>
                </a>
            </div>
            <div class="col">
                <a href="images/skills/6.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Digital Illustration With Procreate">
                    <figure class="gallery-item">
                        <img src="images/skills/6.png" class="img-fluid" alt="Digital Illustration With Procreate">
                        <figcaption>Digital Illustration With Procreate</figcaption>
                    </figure>
                </a>
            </div>
            <div class="col">
                <a href="images/skills/7.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Morning Vinyasa Flow">
                    <figure class="gallery-item">
                        <img src="images/skills/7.png" class="img-fluid" alt="Morning Vinyasa Flow">
                        <figcaption>Morning Vinyasa Flow</figcaption>
                    </figure>
                </a>
            </div>
            <div class="col">
                <a href="images/skills/8.png" data-bs-toggle="modal" data-bs-target="#lightboxModal"
                    data-title="Intro to PHP & My SQL">
                    <figure class="gallery-item">
                        <img src="images/skills/8.png" class="img-fluid" alt="Intro to PHP & My SQL">
                        <figcaption>Intro to PHP & My SQL</figcaption>
                    </figure>
                </a>
            </div>
            <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content gallery-modal">
                        <div class="modal-body p-0">
                            <img id="lightboxImg" src="images/placeholder.png" class="img-fluid rounded-top" alt="">
                        </div>
                        <div class="modal-footer justify-content-between bg-light">
                            <div id="lightboxTitle" class="fw-semibold"></div>
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.inc'; ?>

    <script src="assets/js/script.js"></script>
</body>