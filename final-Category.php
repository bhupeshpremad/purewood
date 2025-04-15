<?php 
    require_once './templates/header.php';
    $baseUrl = AppConfig::baseUrl();
?>

<div class="container-fluid py-4 bg-black text-center sectionHeader">
    <a href="<?= $baseUrl ?>" class="text-decoration-none text-white d-inline-flex align-items-center justify-content-center gap-2">
        <i class="fa fa-arrow-left iconArrow"></i>
        <h2 class="text-white text-uppercase mb-0" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 600;">
            Chairs
        </h2>
    </a>
</div>

<section class="py-5 categoryCard">
    <div class="container py-5">
        <div class="row CategoryGrid g-4">

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="CategoryGrid-card overflow-hidden">
                    <img src="<?= $baseUrl ?>/assets/images/Hotels-and-Cafe-Seating.webp" alt="Indoor Chair" class="img-fluid">
                    <a href="<?= $baseUrl ?>/products/indoor-chair.php">Indoor Chair</a>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 overflow-hidden">
                <div class="CategoryGrid-card overflow-hidden">
                    <img src="<?= $baseUrl ?>/assets/images/Hotels-and-Cafe-Seating.webp" alt="Outdoor Chair" class="img-fluid">
                    <a href="<?= $baseUrl ?>/products/outdoor-chair.php">Outdoor Chair</a>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 overflow-hidden">
                <div class="CategoryGrid-card overflow-hidden">
                    <img src="<?= $baseUrl ?>/assets/images/Hotels-and-Cafe-Seating.webp" alt="Seating Group" class="img-fluid">
                    <a href="<?= $baseUrl ?>/products/seating-group.php">Seating Group</a>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 overflow-hidden">
                <div class="CategoryGrid-card overflow-hidden">
                    <img src="<?= $baseUrl ?>/assets/images/Hotels-and-Cafe-Seating.webp" alt="Stools" class="img-fluid">
                    <a href="<?= $baseUrl ?>/products/stools.php">Stools</a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php 
    require_once './templates/footer.php';
?>
