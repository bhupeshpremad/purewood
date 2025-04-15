<?php
include('../../../config/config.php');

// Set proper header
header('Content-Type: application/json');

// Get category ID from query string
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Validate ID
if ($id > 0) {
    try {
        // Prepare statement to fetch category
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        // Fetch data
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        // If found, return success response
        if ($category) {
            echo json_encode([
                'success' => true,
                'category' => $category
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching category',
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid category ID'
    ]);
}
