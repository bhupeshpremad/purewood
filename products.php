<?php
require_once './templates/header.php';
require_once './config/config.php';

// Get the sub-subcategory slug from the URL
$subsubcategorySlug = isset($_GET['subsubcategory']) ? trim($_GET['subsubcategory']) : '';
$subsubcategory_id = 0;
$subsubcategoryName = "All Products";

// Fetch sub-subcategory details if a slug is provided
if ($subsubcategorySlug !== '') {
    $stmt_sub = $conn->prepare("SELECT id, name FROM subsubcategories WHERE slug = ? AND status = 1");
    $stmt_sub->execute([$subsubcategorySlug]);
    $subRow = $stmt_sub->fetch(PDO::FETCH_ASSOC);

    if ($subRow) {
        $subsubcategory_id = $subRow['id'];
        $subsubcategoryName = $subRow['name'];
    } else {
        // Handle case where the sub-subcategory slug is invalid
        $subsubcategoryName = "Invalid Sub-subcategory";
    }
}

// --- Fetch Products based on the sub-subcategory ---
$whereClause = "";
$params = [];
if ($subsubcategory_id > 0) {
    $whereClause = "WHERE p.subsubcategory_id = :subsubcategory_id";
    $params[':subsubcategory_id'] = $subsubcategory_id;
}

// --- Dynamic Material Filtering (Implementation Needed) ---
// 1. Fetch material categories from the database
// $stmt_materials = $conn->prepare("SELECT * FROM materials");
// $stmt_materials->execute();
// $materials = $stmt_materials->fetchAll(PDO::FETCH_ASSOC);
//
// 2. Build WHERE clause for material filtering based on $_GET parameters
// if (isset($_GET['materials']) && is_array($_GET['materials'])) {
//     $selectedMaterials = $_GET['materials'];
//     if (!empty($selectedMaterials)) {
//         $materialPlaceholders = implode(',', array_fill(0, count($selectedMaterials), '?'));
//         $whereClause .= (empty($whereClause) ? "WHERE" : " AND") . " p.material IN ($materialPlaceholders)";
//         $params = array_merge($params, $selectedMaterials);
//     }
// }

// Dummy total products count (replace with actual database query considering filters)
$stmt_count = $conn->prepare("SELECT COUNT(p.id) FROM products p $whereClause");
$stmt_count->execute($params);
$total_products = $stmt_count->fetchColumn();

// Get parameters from URL or set defaults
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$view_type = isset($_GET['view']) ? $_GET['view'] : 'grid'; // Added view type parameter

// Calculate total pages
$total_pages = ceil($total_products / $per_page);

// Validate current page
$current_page = max(1, min($current_page, $total_pages));

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $per_page;

// Function to generate pagination links with all parameters (including potential material filters)
function getPaginationLink($page, $per_page, $view_type, $subsubcategorySlug = '', $selectedMaterials = []) {
    $url = "?per_page=" . $per_page . "&page=" . $page . "&view=" . $view_type;
    if ($subsubcategorySlug !== '') {
        $url .= "&subsubcategory=" . $subsubcategorySlug;
    }
    if (!empty($selectedMaterials)) {
        foreach ($selectedMaterials as $material) {
            $url .= "&materials[]=" . urlencode($material);
        }
    }
    return $url;
}

// Static Material filters data (replace with dynamic fetch)
$materials = [
    'cane' => 'Cane',
    'fabric' => 'Fabric',
    'leather-leatherette' => 'Leather/Leatherette',
    'marble' => 'Marble',
    'metal' => 'Metal',
    'wood' => 'Wood'
];

// --- Fetch Actual Products from the Database ---
$sql = "SELECT p.id, p.name, p.code, p.image FROM products p $whereClause LIMIT :limit OFFSET :offset";
$stmt_products = $conn->prepare($sql);
$stmt_products->bindParam(':limit', $per_page, PDO::PARAM_INT);
$stmt_products->bindParam(':offset', $offset, PDO::PARAM_INT);
if ($subsubcategory_id > 0) {
    $stmt_products->bindParam(':subsubcategory_id', $subsubcategory_id, PDO::PARAM_INT);
}
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid py-4 bg-black text-center sectionHeader">
    <a href="#" class="text-decoration-none text-white d-inline-flex align-items-center justify-content-center gap-2">
        <i class="fa fa-arrow-left iconArrow"></i>
        <h2 class="text-white text-uppercase mb-0" style="font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 600;">
            <?= htmlspecialchars($subsubcategoryName) ?>
        </h2>
    </a>
</div>

<div class="container-fluid m-0 p-0 bg-white">
    <div class="container p-2">
        <div class="row m-0">

            <div class="offcanvas offcanvas-start" style="width: 300px;" tabindex="-1" id="filterOffcanvas">
                <div class="offcanvas-header bg-white border-bottom border-1">
                    <h5 class="offcanvas-title font-dmsans fw-600 fs-20 text-black">Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="card border-0">
                        <div class="card-body p-3">
                            <div class="filter-section">
                                <h6 class="filter-title font-dmsans fw-600 fs-18 text-black mb-3">Filter by Material</h6>
                                <div class="filter-content">
                                    <?php foreach ($materials as $value => $label): ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input border-1" type="checkbox" id="filter_<?= $value ?>" value="<?= $value ?>">
                                            <label class="form-check-label font-inter fw-400 fs-16 text-black" for="filter_<?= $value ?>"><?= $label ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-12 order-lg-first order-md-first order-last mb-4 d-none d-md-block" id="productSidebar">
                <div class="card border-0">
                    <div class="card-body p-3">
                        <div class="filter-section">
                            <h6 class="filter-title font-dmsans fw-600 fs-18 text-black mb-3">Filter by Material</h6>
                            <div class="filter-content">
                                <?php foreach ($materials as $value => $label): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input border-1" type="checkbox" id="filter_<?= $value ?>_desktop" value="<?= $value ?>">
                                        <label class="form-check-label font-inter fw-400 fs-16 text-black" for="filter_<?= $value ?>_desktop"><?= $label ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-9 col-12 p-0 p-md-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-2 p-md-0">
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb" class="me-3">
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="/index.php" class="text-black">Home</a></li>
                                <li class="breadcrumb-item active text-black"><?= htmlspecialchars($subsubcategoryName) ?></li>
                            </ol>
                        </nav>
                        <p class="text-muted d-none d-md-block mb-0">Showing <?= ($total_products > 0) ? ($offset + 1) : 0 ?>–<?= min($offset + $per_page, $total_products) ?> of <?= $total_products ?> results</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn d-md-none me-2" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                            <i class="fas fa-filter me-1"></i>
                        </button>
                        <div class="d-flex align-items-center shop-tools">
                            <div class="me-2 d-none d-md-block">
                                <span class="me-2">Show:</span>
                                <div class="btn-group btn-group-sm">
                                    <?php foreach ([9, 12, 18, 24] as $option) : ?>
                                        <a href="<?= getPaginationLink(1, $option, $view_type, $subsubcategorySlug) ?>" class="btn <?= $per_page == $option ? 'bg-white text-black active' : 'bg-black text-white' ?>"><?= $option ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="btn-group btn-group-sm me-2">
                                <a href="<?= getPaginationLink($current_page, $per_page, 'grid', $subsubcategorySlug) ?>" class="btn <?= $view_type == 'grid' ? 'bg-white text-black' : 'bg-black text-white' ?>">
                                    <i class="fas fa-th"></i>
                                </a>
                                <a href="<?= getPaginationLink($current_page, $per_page, 'large', $subsubcategorySlug) ?>" class="btn <?= $view_type == 'large' ? 'bg-white text-black' : 'bg-black text-white' ?>">
                                    <i class="fas fa-th-large"></i>
                                </a>
                                <a href="<?= getPaginationLink($current_page, $per_page, 'list', $subsubcategorySlug) ?>" class="btn <?= $view_type == 'list' ? 'bg-white text-black' : 'bg-black text-white' ?>">
                                    <i class="fas fa-th-list"></i>
                                </a>
                            </div>
                            <form class="mb-0">
                                <select class="form-select form-select-sm" id="sorting" name="sorting">
                                    <option value="" selected>Default sorting</option>
                                    <option value="popularity">Popularity</option>
                                    <option value="date">Latest</option>
                                    <option value="price">Price: low to high</option>
                                    <option value="price-desc">Price: high to low</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <p class="text-muted d-md-none p-2">Showing <?= ($total_products > 0) ? ($offset + 1) : 0 ?>–<?= min($offset + $per_page, $total_products) ?> of <?= $total_products ?> results</p>

                <div class="row g-4 m-0 p-2 p-md-0 <?= $view_type == 'list' ? 'product-list-view' : '' ?>">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="<?= $view_type == 'list' ? 'col-12' : ($view_type == 'large' ? 'col-lg-4 col-md-4 col-12' : 'col-lg-3 col-md-3 col-6') ?> p-1 p-md-2">
                                <div class="card product-card h-100 border-0 transition <?= $view_type == 'list' ? 'flex-row' : 'flex-column' ?>">
                                    <a href="product-details.php?id=<?= htmlspecialchars($product['id']) ?>" class="product-image-link <?= $view_type == 'list' ? 'col-md-3' : '' ?>">
                                        <img src="./uploads/products/<?= htmlspecialchars($product['code']) ?>/<?= htmlspecialchars($product['image']) ?>" class="card-img-top rounded-0" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                                    </a>
                                    <div class="card-body p-3 <?= $view_type == 'list' ? 'col-md-9 text-left' : 'text-center' ?>">
                                        <h5 class="card-title">
                                            <a href="product-details.php?id=<?= htmlspecialchars($product['id']) ?>" class="text-black text-uppercase fs-16 fw-500 mb-3 text-decoration-none"><?= htmlspecialchars($product['name']) ?></a>
                                        </h5>
                                        <?php if ($view_type == 'list'): ?>
                                            <p class="card-text">Product code: <?= htmlspecialchars($product['code']) ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-center mt-3">
                                            <button class="btn bg-black text-white fs-16 fw-500 px-4 rounded-0 text-uppercase">Add to quote</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <p class="fs-5">No products found for <?= htmlspecialchars($subsubcategoryName) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <nav aria-label="Page navigation" class="mt-5 p-2 p-md-0">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                            <a class="page-link bg-dark text-white" href="<?= getPaginationLink(max(1, $current_page - 1), $per_page, $view_type, $subsubcategorySlug) ?>">Previous</a>
                        </li>

                        <li class="page-item <?= $current_page == 1 ? 'active' : '' ?>">
                            <a class="page-link <?= $current_page == 1 ? 'bg-black text-white' : 'text-dark' ?>" href="<?= getPaginationLink(1, $per_page, $view_type, $subsubcategorySlug) ?>">1</a>
                        </li>

                        <?php
                        $start_page = max(2, $current_page - 2);
                        $end_page = min($total_pages - 1, $current_page + 2);

                        if ($start_page > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link text-dark">...</span>
                            </li>
                        <?php endif;

                        for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $current_page == $i ? 'active' : '' ?>">
                                <a class="page-link <?= $current_page == $i ? 'bg-black text-white' : 'text-dark' ?>" href="<?= getPaginationLink($i, $per_page, $view_type, $subsubcategorySlug) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor;

                        if ($end_page < $total_pages - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link text-dark">...</span>
                            </li>
                        <?php endif;

                        if ($total_pages > 1): ?>
                            <li class="page-item <?= $current_page == $total_pages ? 'active' : '' ?>">
                                <a class="page-link <?= $current_page == $total_pages ? 'bg-black text-white' : 'text-dark' ?>" href="<?= getPaginationLink($total_pages, $per_page, $view_type, $subsubcategorySlug) ?>"><?= $total_pages ?></a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link bg-dark text-white" href="<?= getPaginationLink(min($total_pages, $current_page + 1), $per_page, $view_type, $subsubcategorySlug) ?>">Next</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>

<?php require_once './templates/footer.php'; ?>