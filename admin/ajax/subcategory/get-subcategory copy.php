<?php
include '../../../config/config.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID is required']);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM subcategories WHERE id = ?");
    $stmt->execute([$id]);
    $subcategory = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($subcategory) {
        echo json_encode(['success' => true, 'subcategory' => $subcategory]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Subcategory not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Something went wrong']);
}
?>
