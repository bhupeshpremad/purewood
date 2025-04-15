<?php
include '../../../config/config.php';

$response = ['status' => false, 'message' => 'Invalid request'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("
        SELECT ss.*, c.name AS category_name, s.name AS subcategory_name
        FROM subsubcategories ss
        LEFT JOIN categories c ON ss.category_id = c.id
        LEFT JOIN subcategories s ON ss.subcategory_id = s.id
        WHERE ss.id = ?
    ");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $response = [
            'status' => true,
            'data' => [
                'id' => $row['id'],
                'name' => $row['name'],
                'slug' => $row['slug'],
                'category_id' => $row['category_id'],
                'subcategory_id' => $row['subcategory_id'],
                'category_name' => $row['category_name'] ?? 'N/A',
                'subcategory_name' => $row['subcategory_name'] ?? 'N/A',
                'image' => $row['image'],
                'status' => $row['status'],
                'created_at' => $row['created_at']
            ]
        ];
    } else {
        $response['message'] = "No sub-subcategory found with given ID.";
    }
}

echo json_encode($response);
