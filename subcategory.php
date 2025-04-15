<?php
require_once './templates/header.php';
require_once './config/config.php';

$categorySlug = isset($_GET['category']) ? trim($_GET['category']) : '';
$category_id = 0;
$categoryName = '';

if ($categorySlug !== '') {
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE slug = ?");
    $stmt->execute([$categorySlug]);
    $catRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($catRow) {
        $category_id = $catRow['id'];
        $categoryName = $catRow['name'];
    }
}

$subcategories = [];
if ($category_id) {
    $stmt = $conn->prepare("SELECT * FROM subcategories WHERE category_id = ? AND status = 1");
    $stmt->execute([$category_id]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid py-4 bg-black text-center sectionHeader">
    <a href="#" class="text-decoration-none text-white d-inline-flex align-items-center justify-content-center gap-2">
        <i class="fa fa-arrow-left iconArrow"></i>
        <h2 class="text-white text-uppercase mb-0" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 600;">
            <?= htmlspecialchars($categoryName) ?>
        </h2>
    </a>
</div>

<section class="py-5 categoryCard">
    <div class="container py-5">
        <div class="row CategoryGrid g-4">
            <?php if (!empty($subcategories)): ?>
                <?php foreach ($subcategories as $sub): ?>
                    <?php
                    $subSlug = $sub['slug'];
                    $subName = htmlspecialchars($sub['name']);
                    ?>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="CategoryGrid-card overflow-hidden">
                            <img src="<?= $baseUrl ?>/admin/uploads/subcategory/<?= htmlspecialchars($sub['image']) ?>" alt="<?= $subName ?>" class="img-fluid w-100">
                            <a href="<?= $baseUrl ?>/sub-Subcategory.php?subcategory=<?= urlencode($subSlug) ?>" class="text-decoration-none d-block text-center mt-2">
                                <?= $subName ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No subcategories found for this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once './templates/footer.php'; ?>