<?php
require_once './templates/header.php';
require_once './config/config.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container py-5'><h3>Product not found.</h3></div>";
    require_once './templates/footer.php';
    exit;
}

$baseUrl = AppConfig::baseUrl();
?>

<section class="single-product py-5">
  <div class="container">
    <div class="row g-4">

      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" id="productImg">
        <div class="main-image" id="lightgallery">
          <?php
            $mainImg = $baseUrl . "/uploads/products/" . $product['code'] . "/" . $product['image'];
          ?>
          <a href="<?= $mainImg ?>" class="gallery-item">
            <img src="<?= $mainImg ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid w-100 rounded-3" id="mainProductImage">
          </a>

          <?php
            $other_images = [];
            if (!empty($product['other_images'])) {
                $other_images = json_decode($product['other_images'], true);
                if (is_array($other_images)) {
                    foreach ($other_images as $img) {
                        $imgPath = $baseUrl . "/uploads/products/" . $product['code'] . "/" . $img;
                        echo '<a href="' . $imgPath . '" class="gallery-item d-none"></a>';
                    }
                }
            }
          ?>
        </div>

        <div class="thumbnail-slider splide mt-3" id="splide01">
          <div class="splide__track">
            <ul class="splide__list">
              <li class="splide__slide">
                <img src="<?= $mainImg ?>" data-fullsize="<?= $mainImg ?>" alt="Main View">
              </li>

              <?php
                if (!empty($other_images) && is_array($other_images)) {
                    foreach ($other_images as $img) {
                        $imgPath = $baseUrl . "/uploads/products/" . $product['code'] . "/" . $img;
                        echo '<li class="splide__slide"><img src="' . $imgPath . '" data-fullsize="' . $imgPath . '" alt="View"></li>';
                    }
                }
              ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
        <h1 class="font-dmsans fw-600 mb-3"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="text-muted mb-4"><?= htmlspecialchars($product['short_description']) ?></p>

        <div class="product-details mb-5">
          <h4 class="font-dmsans fw-600 mb-3 border-bottom pb-2">Product Details</h4>
          <table class="table table-bordered">
            <tbody>
              <tr><th>Product Name</th><td><?= $product['name'] ?></td></tr>
              <tr><th>Product Code</th><td><?= $product['code'] ?></td></tr>
              <tr><th>Brand Name</th><td><?= $product['brand_name'] ?></td></tr>
              <tr><th>Primary Material</th><td><?= $product['primary_material'] ?></td></tr>
              <tr><th>Secondary Material</th><td><?= $product['secondary_material'] ?></td></tr>
              <tr><th>Finish & Appearance</th><td><?= $product['finish_appearance'] ?></td></tr>
              <tr><th>Color Variant</th><td><?= $product['color_variant'] ?></td></tr>
              <tr><th>Product Dimension</th><td><?= $product['dimensions'] ?></td></tr>
              <tr><th>Packing Box Size</th><td><?= $product['packing_box_size'] ?></td></tr>
              <tr><th>Packing Type</th><td><?= $product['packing_type'] ?></td></tr>
              <tr><th>Product Weight</th><td><?= $product['weight'] ?> kg</td></tr>
              <tr><th>Product CBM</th><td><?= $product['cbm'] ?> m³</td></tr>
              <tr><th>Product Loadability</th><td><?= $product['loadability'] ?></td></tr>
              <tr><th>Assembly Required</th><td><?= $product['assembly_required'] ?></td></tr>
              <tr><th>Assembly Tools</th><td><?= $product['assembly_tools'] ?></td></tr>
              <tr><th>Customization Option</th><td><?= $product['customization_options'] ?></td></tr>
              <tr><th>Price per 20pcs</th><td>$<?= $product['price_per_20pcs'] ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="admin-details mt-4">
          <h4 class="font-dmsans fw-600 mb-3 border-bottom pb-2">Admin Details</h4>
          <table class="table table-bordered">
            <tbody>
              <tr><th>Material</th><td><?= $product['material'] ?></td></tr>
              <tr><th>Primary Material</th><td><?= $product['primary_material'] ?></td></tr>
              <tr><th>Secondary Material</th><td><?= $product['secondary_material'] ?></td></tr>
              <tr><th>Finish & Appearance</th><td><?= $product['finish_appearance'] ?></td></tr>
              <tr><th>Color Variants</th><td><?= $product['color_variant'] ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex gap-3 mt-4">
          <button class="btn bg-black text-white px-4 py-2 rounded-0 fs-16 fw-600 text-uppercase">Add to Quote</button>
          <button class="btn btn-outline-dark px-4 py-2 rounded-0 fs-16 fw-600 text-uppercase">Download Catalog</button>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require_once './templates/footer.php'; ?>
