<?php
include '../../../config/config.php';

$response = ['status' => false, 'message' => 'Invalid category ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    $stmt = $conn->prepare("SELECT id, name FROM subcategories WHERE category_id = ? ORDER BY name ASC");
    $stmt->execute([$category_id]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        $response = [
            'status' => true,
            'data' => $data
        ];
    } else {
        $response['message'] = 'No subcategories found';
    }
}

echo json_encode($response);
?>
