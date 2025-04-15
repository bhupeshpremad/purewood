<?php
require_once '../../../config/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if ($id === '') {
        $response['message'] = 'Invalid banner ID';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("SELECT image FROM banners WHERE id = ?");
    $stmt->execute([$id]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$banner) {
        $response['message'] = 'Banner not found';
        echo json_encode($response);
        exit;
    }

    $delete = $conn->prepare("DELETE FROM banners WHERE id = ?");
    if ($delete->execute([$id])) {
        $imagePath = '../uploads/banners/' . $banner['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $response['success'] = true;
        $response['message'] = 'Banner deleted successfully';
    } else {
        $response['message'] = 'Failed to delete banner';
    }
}

echo json_encode($response);
