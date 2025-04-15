<?php
include '../../../config/config.php';

$stmt = $conn->prepare("
    SELECT ss.*, c.name AS category_name, s.name AS subcategory_name
    FROM subsubcategories ss
    LEFT JOIN categories c ON ss.category_id = c.id
    LEFT JOIN subcategories s ON ss.subcategory_id = s.id
    ORDER BY ss.id DESC
");
$stmt->execute();
$data = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'slug' => $row['slug'],
        'category_name' => $row['category_name'] ?? 'N/A', 
        'subcategory_name' => $row['subcategory_name'] ?? 'N/A', 
        'image' => $row['image'],
        'status' => $row['status'],
        'created_at' => $row['created_at']
    ];
}

echo json_encode(['status' => true, 'data' => $data]);
