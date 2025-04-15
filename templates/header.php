<?php
require_once __DIR__ . '/../config/config.php';
$baseUrl = AppConfig::baseUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purewood</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.4.0/css/lightgallery-bundle.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<!-- Header Start -->
<header class="py-2" id="siteHeader">
  <div class="container">
    <div class="row align-items-center">

      <!-- Logo Area -->
      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-6">
        <a class="navbar-brand d-block py-1" href="<?= $baseUrl ?>/index.php">
          <img src="<?= $baseUrl ?>/assets/images/Purewood-F-logo.svg" alt="Purewood Logo" class="img-fluid transition" />
        </a>
      </div>

      <!-- Navigation Links (Desktop) -->
      <div class="col-xl-8 col-lg-8 col-md-8 col-sm-4 d-none d-md-block">
        <ul class="navbar-nav flex-row justify-content-end font-inter fw-500 fs-16">
          <li class="nav-item"><a class="nav-link text-black transition" href="<?= $baseUrl ?>/index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link text-black transition" href="<?= $baseUrl ?>/subcategory.php">Hotels and Cafe Furniture</a></li>
          <li class="nav-item"><a class="nav-link text-black transition" href="<?= $baseUrl ?>/subcategory.php">Home Furniture</a></li>
        </ul>
      </div>

      <!-- Search and Cart (Desktop) -->
      <div class="col-xl-1 col-lg-1 col-md-1 d-none d-md-flex justify-content-end align-items-center nav-icons position-relative">

        <!-- Search Icon -->
        <i class="fas fa-search me-3 transition" id="desktopSearchIcon" style="cursor:pointer;"></i>

        <!-- Search Input Box -->
        <div id="desktopSearchBox" class="position-absolute top-100 end-0 mt-2 bg-white border rounded p-2 shadow d-none" style="z-index: 1000; width: 250px;">
          <div class="d-flex align-items-center">
            <input type="text" class="form-control form-control-sm me-2" placeholder="Search here..." id="searchInputField">
            <button class="btn btn-sm btn-outline-danger" id="closeSearchBox">&times;</button>
          </div>
        </div>

        <!-- Cart Icon as Link -->
        <a href="<?= $baseUrl ?>/cart.php" class="text-dark">
          <i class="fas fa-shopping-cart me-3 transition"></i>
        </a>
      </div>

      <!-- Mobile Menu Icons -->
      <div class="col-xl-2 col-lg-3 col-md-2 col-sm-8 col-6 d-flex d-md-none justify-content-end align-items-center nav-icons">
        <i class="fas fa-search me-3 transition"></i>
        <a href="<?= $baseUrl ?>/cart.php" class="text-dark">
          <i class="fas fa-shopping-cart me-3 transition"></i>
        </a>
        <button class="btn btn-sm btn-outline-dark ms-2 rounded-0 transition" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    </div>
  </div>
</header>
<!-- Header End -->

<!-- Offcanvas Mobile Menu -->
<div class="offcanvas offcanvas-end bg-white" tabindex="-1" id="mobileMenu">
  <div class="offcanvas-header border-bottom">
    <a class="navbar-brand" href="<?= $baseUrl ?>/index.php">
      <img src="<?= $baseUrl ?>/assets/images/Purewood-F-logo.svg" alt="Purewood Logo" style="width: 70%;" class="transition">
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body font-inter">
    <ul>
      <li><a href="<?= $baseUrl ?>" class="fs-16 text-black transition">Home</a></li>
      <li><a href="<?= $baseUrl ?>/subcategory.php" class="fs-16 text-black transition">Hotels and Cafe Furniture</a></li>
      <li><a href="<?= $baseUrl ?>/subcategory.php" class="fs-16 text-black transition">Home Furniture</a></li>
    </ul>
  </div>
</div>