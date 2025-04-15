<?php
require_once './templates/header.php';
require_once './config/config.php';

$subcategorySlug = isset($_GET['subcategory']) ? trim($_GET['subcategory']) : '';
$subcategory_id = 0;
$subcategoryName = "";

if ($subcategorySlug !== '') {
    $stmt = $conn->prepare("SELECT id, name FROM subcategories WHERE slug = ?");
    $stmt->execute([$subcategorySlug]);
    $subRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($subRow) {
        $subcategory_id = $subRow['id'];
        $subcategoryName = $subRow['name'];
    } else {
        $subcategoryName = "Unknown Subcategory";
    }
} else {
    $subcategoryName = "No Subcategory";
}

$subsubcategories = [];
if ($subcategory_id) {
    $stmt = $conn->prepare("SELECT id, name, slug, image FROM subsubcategories WHERE subcategory_id = ? AND status = 1");
    $stmt->execute([$subcategory_id]);
    $subsubcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container-fluid py-4 bg-black text-center sectionHeader">
    <a href="#" class="text-decoration-none text-white d-inline-flex align-items-center justify-content-center gap-2">
        <i class="fa fa-arrow-left iconArrow"></i>
        <h2 class="text-white text-uppercase mb-0" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 600;">
            <?= htmlspecialchars($subcategoryName) ?>
        </h2>
    </a>
</div>

<section class="py-5 categoryCard">
    <div class="container py-5">
        <div class="row CategoryGrid g-4">
            <?php if (!empty($subsubcategories)): ?>
                <?php foreach ($subsubcategories as $subsub): ?>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="CategoryGrid-card overflow-hidden">
                            <img
                                src="<?= $baseUrl ?>/admin/<?= htmlspecialchars($subsub['image']) ?>"
                                alt="<?= htmlspecialchars($subsub['name']) ?>"
                                class="img-fluid w-100"
                            >
                            <a
                                href="<?= $baseUrl ?>/products.php?subsubcategory=<?= htmlspecialchars($subsub['slug']) ?>"
                                class="text-decoration-none d-block text-center mt-2"
                            >
                                <?= htmlspecialchars($subsub['name']) ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No sub-subcategories found for this subcategory.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once './templates/footer.php'; ?>