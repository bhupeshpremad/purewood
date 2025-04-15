<?php
require_once './templates/header.php';
require_once './config/config.php';
$baseUrl = AppConfig::baseUrl();
?>

<?php
$stmt = $conn->prepare("SELECT * FROM banners WHERE status = 1 ORDER BY id DESC");
$stmt->execute();
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($banners)): ?>
    <section class="splide" id="bannerSlider" aria-label="Banner Slider">
        <div class="splide__track">
            <ul class="splide__list">
                <?php foreach ($banners as $banner): ?>
                    <li class="splide__slide">
                        <img
                            src="<?= htmlspecialchars($baseUrl . '/admin/uploads/banners/' . $banner['image']) ?>"
                            alt="<?= htmlspecialchars($banner['alt_tag'] ?: 'Banner Image') ?>"
                            class="img-fluid w-100"
                            loading="lazy"
                        >
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>

<section class="py-5 categoryCard">
    <div class="container py-5">
        <h2 class="text-center m-auto fs-45 fw-600 mb-4">Our Products</h2>
        <p class="text-center max-w-70 m-auto fs-18 fw-600 mb-4">
            Explore our exquisite leather product, we offer premium quality items crafted with precision and style,
            designed to elevate your everyday experience.
        </p>
    </div>

    <div class="container pt-2">
        <div class="row CategoryGrid g-4">
            <?php
            $stmt = $conn->prepare("SELECT * FROM categories WHERE status = 1 ORDER BY id DESC");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($categories)):
                foreach ($categories as $cat):
                    $name = strtoupper($cat['name']);
                    $slug = $cat['slug'];
                    $image = $cat['image'];
            ?>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="CategoryGrid-card overflow-hidden">
                        <img
                            src="<?= $baseUrl ?>/admin/uploads/category/<?= htmlspecialchars($image); ?>"
                            alt="<?= htmlspecialchars($name); ?>"
                            class="img-fluid"
                            loading="lazy"
                        >
                        <a href="<?= $baseUrl ?>/subcategory.php?category=<?= urlencode($slug); ?>">
                            <?= $name; ?>
                        </a>
                    </div>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>

<?php
require_once './templates/footer.php';
?>