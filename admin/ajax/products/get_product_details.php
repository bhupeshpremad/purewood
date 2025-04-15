<?php
include '../../../config/config.php';

if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        if ($product['other_images']) {
            $product['other_images'] = json_decode($product['other_images'], true);
        } else {
            $product['other_images'] = [];
        }

        if ($product['material'] !== null && $product['material'] !== '') {
            if (strpos($product['material'], ',') !== false) {
                $product['material'] = explode(',', $product['material']);
            } else {
                $product['material'] = [$product['material']]; 
            }
        } else {
            $product['material'] = [];
        }

        if ($product['customization_options']) {
            $product['customization_options'] = explode(',', $product['customization_options']);
        } else {
            $product['customization_options'] = [];
        }

        echo json_encode(['success' => true, 'data' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
}
?>