<?php
include '../../../config/config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if product ID and status are set
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['status']) && is_numeric($_POST['status'])) {
        $product_id = intval($_POST['id']);
        $new_status = intval($_POST['status']);

        // Validate status value (should be 0 or 1)
        if ($new_status === 0 || $new_status === 1) {
            try {
                // Prepare the SQL update statement
                $sql = "UPDATE products SET status = :status WHERE id = :id";
                $stmt = $conn->prepare($sql);

                // Bind the parameters
                $stmt->bindParam(':status', $new_status, PDO::PARAM_INT);
                $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);

                // Execute the statement
                if ($stmt->execute()) {
                    // Status updated successfully
                    $response = ['status' => true, 'message' => 'Product status updated successfully.'];
                } else {
                    // Error updating status
                    $response = ['status' => false, 'message' => 'Error updating product status.'];
                }
            } catch (PDOException $e) {
                // Database error
                $response = ['status' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        } else {
            // Invalid status value
            $response = ['status' => false, 'message' => 'Invalid status value. Status must be 0 or 1.'];
        }
    } else {
        // Product ID or status not provided
        $response = ['status' => false, 'message' => 'Product ID or status not provided.'];
    }
} else {
    // Invalid request method
    $response = ['status' => false, 'message' => 'Invalid request method. Only POST requests are allowed.'];
}

// Set the content type to JSON
header('Content-Type: application/json');

// Encode and send the response
echo json_encode($response);
?>