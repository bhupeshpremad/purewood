<?php
require_once __DIR__ . '/../../config/config.php';
$baseUrl = AppConfig::baseUrl();

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . $baseUrl . "/admin/index.php");
    exit();
}
?>

<div id="sidebar" class="sidebar p-3 position-relative">
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="text-center mb-4">
        <img src="<?= $baseUrl ?>/assets/images/Purewood-F-logo-Whit.svg" class="img-fluid logo" alt="Logo">
    </div>

    <ul class="nav flex-column">
        <li class="nav-item mb-1">
            <a href="<?= $baseUrl ?>/admin/dashboard.php" class="nav-link text-white d-flex align-items-center">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <div class="accordion accordion-flush" id="adminMenu">

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse">
                        <i class="fas fa-tags me-2"></i> <span class="link-text">Category Manage</span>
                    </button>
                </h2>
                <div id="categoryCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/category/add.php" class="d-block text-white text-decoration-none mb-2">Add Category</a>
                        <a href="<?= $baseUrl ?>/admin/category/view.php" class="d-block text-white text-decoration-none mb-2">View Category</a>
                    </div>
                </div>
            </div>

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#subCategoryCollapse">
                        <i class="fas fa-sitemap me-2"></i> <span class="link-text">Subcategory Manage</span>
                    </button>
                </h2>
                <div id="subCategoryCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/subcategory/add.php" class="d-block text-white text-decoration-none mb-2">Add Subcategory</a>
                        <a href="<?= $baseUrl ?>/admin/subcategory/view.php" class="d-block text-white text-decoration-none mb-2">View Subcategory</a>
                    </div>
                </div>
            </div>

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#subSubCategoryCollapse">
                        <i class="fas fa-layer-group me-2"></i> <span class="link-text">Sub-Subcategory Manage</span>
                    </button>
                </h2>
                <div id="subSubCategoryCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/subsubcategory/add.php" class="d-block text-white text-decoration-none mb-2">Add Sub-Subcategory</a>
                        <a href="<?= $baseUrl ?>/admin/subsubcategory/view.php" class="d-block text-white text-decoration-none mb-2">View Sub-Subcategory</a>
                    </div>
                </div>
            </div>

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#productCollapse">
                        <i class="fas fa-box-open me-2"></i> <span class="link-text">Product Manage</span>
                    </button>
                </h2>
                <div id="productCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/products/add.php" class="d-block text-white text-decoration-none mb-2">Add Product</a>
                        <a href="<?= $baseUrl ?>/admin/products/view.php" class="d-block text-white text-decoration-none mb-2">View Products</a>
                    </div>
                </div>
            </div>

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#bannerCollapse">
                        <i class="fas fa-image me-2"></i> <span class="link-text">Banner Manage</span>
                    </button>
                </h2>
                <div id="bannerCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/banner/add.php" class="d-block text-white text-decoration-none mb-2">Add Banner</a>
                        <a href="<?= $baseUrl ?>/admin/banner/view.php" class="d-block text-white text-decoration-none mb-2">View Banners</a>
                    </div>
                </div>
            </div>

            <div class="accordion-item bg-dark border-0 mb-1">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white py-2 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#userCollapse">
                        <i class="fas fa-users me-2"></i> <span class="link-text">User Manage</span>
                    </button>
                </h2>
                <div id="userCollapse" class="accordion-collapse collapse" data-bs-parent="#adminMenu">
                    <div class="accordion-body px-3 py-1">
                        <a href="<?= $baseUrl ?>/admin/user/add.php" class="d-block text-white text-decoration-none mb-2">Add User</a>
                        <a href="<?= $baseUrl ?>/admin/user/view.php" class="d-block text-white text-decoration-none mb-2">View Users</a>
                    </div>
                </div>
            </div>

        </div>
    </ul>
</div>