<?php
include '../../../config/config.php';

header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT id, name, alt_tag, image, status FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            echo json_encode(['success' => true, 'banner' => $banner]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Banner not found.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        http_response_code(500);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid banner ID.']);
}
?>