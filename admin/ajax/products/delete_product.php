<?php
include '../../../config/config.php'; // Apne configuration file ka path sahi karein

// Check if product ID is received
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $product_id = intval($_POST['id']);

    try {
        // Optional: Check if product exists before deleting
        $check_sql = "SELECT image, code FROM products WHERE id = :id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $check_stmt->execute();
        $product_data = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($product_data) {
            // Delete product from database
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Optional: Delete associated images from the file system
                $image_path = '../../uploads/products/' . $product_data['code'] . '/' . $product_data['image'];
                if (!empty($product_data['image']) && file_exists($image_path)) {
                    if (unlink($image_path)) {
                        // Image deleted successfully
                    } else {
                        // Error deleting image (log this if needed)
                    }
                    // Optionally delete other images as well
                }

                echo json_encode(['status' => true, 'message' => 'Product deleted successfully.']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Error deleting product from database.']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Product not found.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => false, 'message' => 'Invalid product ID.']);
}
?>